<?php declare(strict_types=1);

namespace App\Services\Model\Invoice;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Invoice as Model;
use App\Services;
use App\Services\Model\RequestAbstract;

class Request extends RequestAbstract
{
    /**
     * @return array
     */
    public function index(): array
    {
        return $this->fractal('simple', $this->model()->list()->get());
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
     * @return string
     */
    public function preview(): string
    {
        return $this->detailPreview($this->model()->select('id')->orderBy('date_at', 'DESC')->firstOrFail()->id);
    }

    /**
     * @return string
     */
    public function previewCached(): string
    {
        return $this->cache(__METHOD__, fn () => $this->preview());
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
     * @param int $id
     *
     * @return string
     */
    public function detailPreview(int $id): string
    {
        return Services\Model\InvoiceFile\StoreGenerator::html($this->modelDetailById($id));
    }

    /**
     * @param int $id
     *
     * @return string
     */
    public function detailPreviewCached(int $id): string
    {
        return $this->cache(__METHOD__, fn () => $this->detailPreview($id));
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
     * @return ?array
     */
    protected function fractal(string $name, $data): ?array
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
     * @return \App\Services\Model\Invoice\Store
     */
    protected function store(array $data = []): Store
    {
        return $this->store ?? ($this->store = new Store($this->user, $data));
    }
}
