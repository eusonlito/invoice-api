<?php declare(strict_types=1);

namespace App\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use App\Exceptions;

class UserCompany
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (empty($request->user()->company)) {
            throw new Exceptions\NotAllowedException();
        }

        return $next($request);
    }
}
