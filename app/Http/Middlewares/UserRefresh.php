<?php declare(strict_types=1);

namespace App\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use App\Services;

class UserRefresh
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Services\Model\User\StoreAuth::refresh($request);

        return $next($request);
    }
}
