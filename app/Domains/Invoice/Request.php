<?php declare(strict_types=1);

namespace App\Domains\Invoice;

use Illuminate\Database\Eloquent\Builder;
use App\Exceptions\UnexpectedValueException;
use App\Models\Invoice as Model;
use App\Domains\RequestAbstract;

class Request extends RequestAbstract
{
    /**
     * @return array
     */
    public function index(): array
    {
        return $this->fractal('simple', $this->model()->list()->filterByInput($this->request->input())->get());
    }

    /**
     * @return array
     */
    public function indexCached(): array
    {
        return $this->cache(__METHOD__, fn () => $this->index());
    }

    /**
     * @return array
     */
    public function export(): array
    {
        return $this->fractal('export', $this->model()->export()->get());
    }

    /**
     * @return array
     */
    public function exportCached(): array
    {
        return $this->cache(__METHOD__, fn () => $this->export());
    }

    /**
     * @param string $format
     * @param string $filter
     *
     * @return array|string
     */
    public function exportFormatFilter(string $format, string $filter)
    {
        $model = $this->model();

        if ($filter) {
            $model->filterByInput($this->request->input());
        }

        if ($format === 'csv') {
            return Csv::export($model->exportPlain()->get());
        }

        if ($format === 'zip') {
            return Zip::export($model->exportZip()->get());
        }

        return $this->fractal('export', $model->export()->get());
    }

    /**
     * @param string $format
     * @param string $filter
     *
     * @return array|string
     */
    public function exportFormatFilterCached(string $format, string $filter)
    {
        if (in_array($format, ['json', 'csv', 'zip'], true) === false) {
            throw new UnexpectedValueException();
        }

        if ($format === 'zip') {
            return $this->exportFormatFilter($format, $filter);
        }

        return $this->cache(__METHOD__, fn () => $this->exportFormatFilter($format, $filter));
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function detail(int $id): array
    {
        return $this->fractal('detail', $this->modelDetailById($id));
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function detailCached(int $id): array
    {
        return $this->cache(__METHOD__, fn () => $this->detail($id));
    }

    /**
     * @return array
     */
    public function create(): array
    {
        return $this->fractal('detail', $this->store($this->validator('create'))->create());
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function update(int $id): array
    {
        return $this->fractal('detail', $this->store($this->validator('update'))->update($this->modelById($id)));
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function paid(int $id): array
    {
        return $this->fractal('detail', $this->store()->paid($this->modelById($id)));
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function duplicate(int $id): array
    {
        return $this->fractal('detail', (new StoreDuplicate($this->user, $this->validator('duplicate')))->invoice($this->modelById($id)));
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function delete(int $id): void
    {
        $this->store()->delete($this->modelById($id));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function model(): Builder
    {
        return Model::byCompany($this->user->company);
    }

    /**
     * @param string $name
     * @param mixed $data
     *
     * @return array
     */
    protected function fractal(string $name, $data): array
    {
        return Fractal::transform($name, $data);
    }

    /**
     * @param string $name
     *
     * @return array
     */
    protected function validator(string $name): array
    {
        return Validator::validate($name, $this->request->all());
    }

    /**
     * @param array $data = []
     *
     * @return \App\Domains\Invoice\Store
     */
    protected function store(array $data = []): Store
    {
        return $this->store ?? ($this->store = new Store($this->user, $data));
    }
}
