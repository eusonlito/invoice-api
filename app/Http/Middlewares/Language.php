<?php declare(strict_types=1);

namespace App\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use App\Services;

class Language
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Services\Config\Language::byRequest($request);

        return $next($request);
    }
}
