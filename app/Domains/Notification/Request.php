<?php declare(strict_types=1);

namespace App\Domains\Notification;

use App\Models\Notification as Model;
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
     * @return int
     */
    public function count(): int
    {
        return $this->model()->unread()->count();
    }

    /**
     * @return int
     */
    public function countCached(): int
    {
        return (int)$this->cache(__METHOD__, fn () => $this->count());
    }

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
    public function last(): array
    {
        return $this->fractal('simple', $this->model()->list()->limit(5)->get());
    }

    /**
     * @return array
     */
    public function lastCached(): array
    {
        return $this->cache(__METHOD__, fn () => $this->last());
    }

    /**
     * @return void
     */
    public function read(): void
    {
        $this->store()->read();
    }
}
