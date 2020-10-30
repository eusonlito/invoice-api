<?php declare(strict_types=1);

namespace App\Http\Middlewares;

use Closure;
use Exception;
use Throwable;
use Illuminate\Http\Request;
use App\Domains\User\Store;
use App\Models\User as Model;

class UserConfirm
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        try {
            (new Store($request, $user, $user))->confirmCheck();
        } catch (Exception $e) {
            return $this->logout($request, $user, $e);
        }

        return $next($request);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Models\User $user
     * @param \Throwable $e
     *
     * @return void
     */
    protected function logout(Request $request, Model $user, Throwable $e)
    {
        (new Store($request, $user, $user))->authLogout();

        throw $e;
    }
}
