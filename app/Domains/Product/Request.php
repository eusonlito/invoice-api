<?php declare(strict_types=1);

namespace App\Domains\Product;

use App\Models\Product as Model;
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
     * @return void
     */
    public function delete(int $id): void
    {
        $this->store($this->modelById($id))->delete();
    }
}
