<?php declare(strict_types=1);

namespace App\Domains;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models;

abstract class RequestAbstract extends CacheAbstract
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected Request $request;

    /**
     * @var ?\App\Models\User
     */
    protected ?Models\User $user;

    /**
     * @var \App\Domains\StoreAbstract
     */
    protected StoreAbstract $store;

    /**
     * @param \Illuminate\Http\Request $request
     * @param ?\App\Models\User $user
     *
     * @return self
     */
    public function __construct(Request $request, ?Models\User $user)
    {
        $this->request = $request;
        $this->user = $user;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    abstract protected function model(): Builder;

    /**
     * @param int $id
     *
     * @return \App\Models\ModelAbstract
     */
    protected function modelById(int $id): Models\ModelAbstract
    {
        return $this->model()->byId($id)->firstOrFail();
    }

    /**
     * @param int $id
     *
     * @return \App\Models\ModelAbstract
     */
    protected function modelDetailById(int $id): Models\ModelAbstract
    {
        return $this->model()->detail()->byId($id)->firstOrFail();
    }
}
