<?php declare(strict_types=1);

namespace App\Domains\Notification;

use Illuminate\Support\Collection;
use App\Domains\RequestAbstract;
use App\Models\Notification as Model;

class Request extends RequestAbstract
{
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
     * @return \Illuminate\Support\Collection
     */
    public function index(): Collection
    {
        return $this->model()->list()->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function last(): Collection
    {
        return $this->model()->list()->limit(5)->get();
    }

    /**
     * @return void
     */
    public function read(): void
    {
        $this->store()->read();
    }
}
