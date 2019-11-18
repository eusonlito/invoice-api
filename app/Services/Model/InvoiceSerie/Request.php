<?php declare(strict_types=1);

namespace App\Services\Model\InvoiceSerie;

use Illuminate\Database\Eloquent\Builder;
use App\Models\InvoiceSerie as Model;
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
    public function enabled(): array
    {
        return $this->fractal('simple', $this->model()->enabled()->list()->get());
    }

    /**
     * @return array
     */
    public function enabledCached(): array
    {
        return $this->cache(__METHOD__, fn () => $this->enabled());
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
     * @return string
     */
    public function css(int $id): string
    {
        return StoreCss::get($this->modelById($id));
    }

    /**
     * @param int $id
     *
     * @return string
     */
    public function cssPreview(int $id): string
    {
        return StoreCss::preview($this->modelById($id), (string)$this->request->input('css'));
    }

    /**
     * @param int $id
     *
     * @return string
     */
    public function cssUpdate(int $id): string
    {
        $this->store($this->validator('css'))->cssUpdate($this->modelById($id));

        return $this->request->input('css');
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
     * @return \App\Services\Model\InvoiceSerie\Store
     */
    protected function store(array $data = []): Store
    {
        return $this->store ?? ($this->store = new Store($this->user, $data));
    }
}
