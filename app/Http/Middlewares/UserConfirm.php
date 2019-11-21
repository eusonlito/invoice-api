<?php declare(strict_types=1);

namespace App\Http\Middlewares;

use Closure;
use Exception;
use Illuminate\Http\Request;
use App\Domains;

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
        try {
            Domains\User\StoreConfirm::check($request->user());
        } catch (Exception $e) {
            return $this->logout($e);
        }

        return $next($request);
    }

    /**
     * @param \Exception $e
     *
     * @return void
     */
    protected function logout(Exception $e)
    {
        Domains\User\StoreAuth::logout();

        throw $e;
    }
}
