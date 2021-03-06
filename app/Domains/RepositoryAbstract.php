<?php declare(strict_types=1);

namespace App\Domains;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\ModelAbstract;
use App\Models\User;

abstract class RepositoryAbstract
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected Request $request;

    /**
     * @var ?\App\Models\User
     */
    protected ?User $user;

    /**
     * @param \Illuminate\Http\Request $request
     * @param ?\App\Models\User $user = null
     *
     * @return self
     */
    final public function __construct(Request $request, ?User $user = null)
    {
        $this->request = $request;
        $this->user = $user;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function model(): Builder
    {
        $class = static::MODEL;

        return $class::byCompany($this->user->company);
    }

    /**
     * @param int $id
     *
     * @return \App\Models\ModelAbstract
     */
    protected function modelById(int $id): ModelAbstract
    {
        return $this->model()->findOrFail($id);
    }

    /**
     * @param int $id
     *
     * @return \App\Models\ModelAbstract
     */
    protected function modelDetailById(int $id): ModelAbstract
    {
        return $this->model()->detail()->findOrFail($id);
    }

    /**
     * @param string $name
     *
     * @return array
     */
    final protected function validator(string $name): array
    {
        return forward_static_call([static::VALIDATOR, 'validate'], $name, $this->request->all());
    }

    /**
     * @param ?\App\Models\ModelAbstract $row = null
     * @param array $data = []
     *
     * @return \App\Domains\StoreAbstract
     */
    final protected function store(?ModelAbstract $row = null, array $data = []): StoreAbstract
    {
        $class = static::STORE;

        return new $class($this->request, $this->user, $row, $data);
    }
}
