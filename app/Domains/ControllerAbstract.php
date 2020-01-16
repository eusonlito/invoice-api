<?php declare(strict_types=1);

namespace App\Domains;

use Illuminate\Http\Request;
use App\Http\Controllers\ControllerAbstract as BaseControllerAbstract;
use App\Models\User;

abstract class ControllerAbstract extends BaseControllerAbstract
{
    use CacheTrait;

    /**
     * @var \Illuminate\Http\Request
     */
    protected Request $request;

    /**
     * @var ?\App\Models\User
     */
    protected ?User $user;

    /**
     * @return self
     */
    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {
            $this->request = $request;
            $this->user = app('user');

            $this->cacheLoad();

            return $next($request);
        });
    }

    /**
     * @return \App\Domains\RepositoryAbstract
     */
    protected function repository(): RepositoryAbstract
    {
        $class = static::REPOSITORY;

        return new $class($this->request, $this->user);
    }

    /**
     * @param string $class
     *
     * @return \App\Domains\RepositoryAbstract
     */
    protected function repositoryFrom(string $class): RepositoryAbstract
    {
        $class = __NAMESPACE__.'\\'.$class.'\\Repository';

        return new $class($this->request, $this->user);
    }
}
