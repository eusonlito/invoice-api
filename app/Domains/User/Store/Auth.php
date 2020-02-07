<?php declare(strict_types=1);

namespace App\Domains\User\Store;

use Illuminate\Http\Request;
use App\Exceptions\AuthenticationException;
use App\Exceptions\ValidatorException;
use App\Models;
use App\Models\User as Model;
use App\Services;
use App\Services\Jwt\Auth as AuthJwt;

class Auth extends StoreAbstract
{
    /**
     * @return \App\Models\User
     */
    public function byCredentials(): Model
    {
        $this->data['user'] = strtolower($this->data['user']);

        Services\Request\IpLock::locked(true);

        if (!AuthJwt::byCredentials($this->data)) {
            $this->fail($this->data['user']);
        }

        $this->row = auth()->user();

        return $this->register();
    }

    /**
     * @return \App\Models\User
     */
    public function byUser(): Model
    {
        AuthJwt::byUser($this->row);

        return $this->register();
    }

    /**
     * @return ?string
     */
    public function token(): ?string
    {
        return tap(AuthJwt::token(), fn () => $this->cacheFlush());
    }

    /**
     * @return \App\Models\User
     */
    protected function register(): Model
    {
        $this->checkEnabled();
        $this->registerSession('login');
        $this->loadRelations();
        $this->cacheFlush();

        return $this->row;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return ?\App\Models\User
     */
    public function refresh(Request $request): ?Model
    {
        return tap(AuthJwt::refresh($request), fn () => $this->cacheFlush());
    }

    /**
     * @return void
     */
    public function logout()
    {
        $this->registerSession('logout');

        AuthJwt::logout();

        $this->cacheFlush();
    }

    /**
     * @param string $user
     *
     * @throws \App\Exceptions\AuthenticationException
     *
     * @return void
     */
    protected function fail(string $user)
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

        throw new AuthenticationException();
    }

    /**
     * @throws \App\Exceptions\ValidatorException
     *
     * @return self
     */
    protected function checkEnabled(): self
    {
        if (empty($this->row->enabled)) {
            throw new ValidatorException(__('exception.user-disabled'));
        }

        return $this;
    }

    /**
     * @return self
     */
    protected function loadRelations(): self
    {
        $this->row->load('language');

        return $this;
    }

    /**
     * @param string $action
     *
     * @return self
     */
    protected function registerSession(string $action): self
    {
        service()->log('user', $action, $this->row->id);

        Models\UserSession::insert([
            'action' => $action,
            'ip' => request()->ip(),
            'user_id' => $this->row->id,
        ]);

        return $this;
    }
}
