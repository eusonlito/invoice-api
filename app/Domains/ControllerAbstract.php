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
    final public function __construct()
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
    final protected function repository(): RepositoryAbstract
    {
        $class = static::REPOSITORY;

        return new $class($this->request, $this->user);
    }

    /**
     * @param string $domain
     *
     * @return \App\Domains\RepositoryAbstract
     */
    final protected function repositoryFrom(string $domain): RepositoryAbstract
    {
        $class = __NAMESPACE__.'\\'.$domain.'\\Repository';

        return new $class($this->request, $this->user);
    }
}
