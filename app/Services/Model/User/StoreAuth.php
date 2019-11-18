<?php declare(strict_types=1);

namespace App\Services\Model\User;

use Illuminate\Http\Request;
use App\Exceptions\AuthenticationException;
use App\Exceptions\ValidatorException;
use App\Models;
use App\Models\User as Model;
use App\Services;

class StoreAuth
{
    /**
     * @param array $credentials
     *
     * @return \App\Models\User
     */
    public static function byCredentials(array $credentials): Model
    {
        $credentials['user'] = strtolower($credentials['user']);

        Services\Request\IpLock::locked(true);

        if (!Services\Jwt\Auth::byCredentials($credentials)) {
            static::fail($credentials['user']);
        }

        return static::register(auth()->user());
    }

    /**
     * @param \App\Models\User $user
     *
     * @return \App\Models\User
     */
    public static function byUser(Model $user): Model
    {
        Services\Jwt\Auth::byUser($user);

        return static::register($user);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Illuminate\Auth\AuthenticationException
     *
     * @return \App\Models\User
     */
    public static function byRequest(Request $request): Model
    {
        $user = Services\Jwt\Auth::authByRequest($request);

        static::checkEnabled($user);
        static::loadRelations($user);

        return $user;
    }

    /**
     * @return ?string
     */
    public static function token(): ?string
    {
        return Services\Jwt\Auth::token();
    }

    /**
     * @param \App\Models\User $user
     *
     * @return \App\Models\User
     */
    protected static function register(Model $user): Model
    {
        static::checkEnabled($user);
        static::registerSession('login');
        static::loadRelations($user);

        return $user;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return ?string
     */
    public static function refresh(Request $request): ?string
    {
        return Services\Jwt\Auth::refresh($request);
    }

    /**
     * @return void
     */
    public static function logout()
    {
        static::registerSession('logout');

        Services\Jwt\Auth::logout();
    }

    /**
     * @param string $user
     *
     * @throws \App\Exceptions\AuthenticationException
     *
     * @return void
     */
    protected static function fail(string $user)
    {
        Models\UserSessionFail::insert([
            'user' => $user,
            'ip' => request()->ip()
        ]);

        Services\Request\IpLock::lockIfFail(
            'session.auth',
            $user,
            (int)config('auth.lock.allowed'),
            (int)config('auth.lock.check')
        );

        throw new AuthenticationException;
    }

    /**
     * @param \App\Models\User $user
     *
     * @throws \App\Exceptions\ValidatorException
     *
     * @return void
     */
    protected static function checkEnabled(Model $user)
    {
        if (empty($user->enabled)) {
            throw new ValidatorException(__('exception.user-disabled'));
        }
    }

    /**
     * @param \App\Models\User $user
     *
     * @return void
     */
    protected static function loadRelations(Model $user)
    {
        $user->load('language');
    }

    /**
     * @param string $action
     *
     * @return void
     */
    protected static function registerSession(string $action)
    {
        $user = auth()->user();

        service()->log('user', $action, $user->id);

        Models\UserSession::insert([
            'action' => $action,
            'ip' => request()->ip(),
            'user_id' => $user->id,
        ]);
    }
}
