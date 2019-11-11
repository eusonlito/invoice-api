<?php declare(strict_types=1);

namespace App\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use App\Services;

class LoggerRequest
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (config('logging.channels.request.enabled') === true) {
            Services\Logger\Request::info($request->url(), [
                'ip' => $request->ip(),
                'headers' => $request->headers->all(),
                'input' => $request->except('password'),
            ]);
        }

        return $next($request);
    }
}
