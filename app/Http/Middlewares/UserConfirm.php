<?php declare(strict_types=1);

namespace App\Http\Middlewares;

use Closure;
use Exception;
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
            (new Store($user, $user))->confirmCheck();
        } catch (Exception $e) {
            return $this->logout($user, $e);
        }

        return $next($request);
    }

    /**
     * @param \App\Models\User $user
     * @param \Exception $e
     *
     * @return void
     */
    protected function logout(Model $user, Exception $e)
    {
        (new Store($user, $user))->authLogout();

        throw $e;
    }
}
