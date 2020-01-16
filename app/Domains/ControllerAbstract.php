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
     * @param \Illuminate\Http\Request $request
     *
     * @return self
     */
    public function __construct(Request $request)
    {
        $this->middleware(function (Request $request, $next) {
            $this->request = $request;
            $this->user = app('user');

            $this->cacheLoad();

            return $next($request);
        });
    }

    /**
     * @return \App\Domains\RequestAbstract
     */
    protected function request(): RequestAbstract
    {
        $class = static::REQUEST;

        return new $class($this->request, $this->user);
    }

    /**
     * @param string $class
     *
     * @return \App\Domains\RequestAbstract
     */
    protected function requestFrom(string $class): RequestAbstract
    {
        $class = __NAMESPACE__.'\\'.$class.'\\Request';

        return new $class($this->request, $this->user);
    }
}
