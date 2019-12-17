<?php declare(strict_types=1);

namespace App\Domains\Invoice;

use App\Exceptions\UnexpectedValueException;
use App\Models\Invoice as Model;
use App\Domains\RequestAbstract;

class Request extends RequestAbstract
{
    /**
     * @const string
     */
    protected const FRACTAL = Fractal::class;

    /**
     * @const string
     */
    protected const MODEL = Model::class;

    /**
     * @const string
     */
    protected const STORE = Store::class;

    /**
     * @const string
     */
    protected const VALIDATOR = Validator::class;

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
            return Service\Csv::export($model->exportCsv()->get());
        }

        if ($format === 'zip') {
            return Service\Zip::export($model->exportZip()->get());
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
        return $this->fractal('detail', $this->store(null, $this->validator('create'))->create());
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function update(int $id): array
    {
        return $this->fractal('detail', $this->store($this->modelById($id), $this->validator('update'))->update());
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function paid(int $id): array
    {
        return $this->fractal('detail', $this->store($this->modelById($id))->paid());
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function duplicate(int $id): array
    {
        return $this->fractal('detail', $this->store($this->modelById($id), $this->validator('duplicate'))->duplicate());
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function delete(int $id): void
    {
        $this->store($this->modelById($id))->delete();
    }
}
