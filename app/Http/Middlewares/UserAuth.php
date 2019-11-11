<?php declare(strict_types=1);

namespace App\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;

class UserAuth
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() === null) {
            throw new AuthenticationException();
        }

        return $next($request);
    }
}
