<?php declare(strict_types=1);

namespace App\Domains\User;

use Illuminate\Http\Request;
use App\Models\User as Model;

class Store extends Store\StoreAbstract
{
    /**
     * @return \App\Models\User
     */
    public function authCredentials(): Model
    {
        return tap($this->storeAuth()->byCredentials(), fn () => $this->cacheFlush());
    }

    /**
     * @return \App\Models\User
     */
    public function authUser(): Model
    {
        return tap($this->storeAuth()->byUser(), fn () => $this->cacheFlush());
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Models\User
     */
    public function authRefresh(Request $request): Model
    {
        return tap($this->storeAuth()->refresh($request), fn () => $this->cacheFlush());
    }

    /**
     * @return ?string
     */
    public function authToken(): ?string
    {
        return tap($this->storeAuth()->token(), fn () => $this->cacheFlush());
    }

    /**
     * @return void
     */
    public function authLogout(): void
    {
        $this->storeAuth()->logout();
    }

    /**
     * @return \App\Models\User
     */
    public function signup(): Model
    {
        return tap($this->storeSignup()->start(), fn () => $this->cacheFlush());
    }

    /**
     * @return \App\Models\User
     */
    public function confirmCheck(): Model
    {
        return $this->storeConfirm()->check();
    }

    /**
     * @return \App\Models\User
     */
    public function confirmStart(): Model
    {
        return tap($this->storeConfirm()->start(), fn () => $this->cacheFlush());
    }

    /**
     * @param string $hash
     *
     * @return \App\Models\User
     */
    public function confirmFinish(string $hash): Model
    {
        return tap($this->storeConfirm()->finish($hash), fn () => $this->cacheFlush());
    }

    /**
     * @return \App\Models\User
     */
    public function updateProfile(): Model
    {
        return tap($this->storeProfile()->profile(), fn () => $this->cacheFlush());
    }

    /**
     * @return \App\Models\User
     */
    public function updatePassword(): Model
    {
        return tap($this->storeProfile()->password(), fn () => $this->cacheFlush());
    }

    /**
     * @return ?\App\Models\User
     */
    public function passwordResetStart(): ?Model
    {
        return tap($this->storePasswordReset()->start(), fn () => $this->cacheFlush());
    }

    /**
     * @param string $hash
     *
     * @return \App\Models\User
     */
    public function passwordResetFinish(string $hash): Model
    {
        return tap($this->storePasswordReset()->finish($hash), fn () => $this->cacheFlush());
    }

    /**
     * @return \App\Domains\User\Store\Auth
     */
    protected function storeAuth(): Store\Auth
    {
        return $this->factory(Store\Auth::class);
    }

    /**
     * @return \App\Domains\User\Store\Confirm
     */
    protected function storeConfirm(): Store\Confirm
    {
        return $this->factory(Store\Confirm::class);
    }

    /**
     * @return \App\Domains\User\Store\PasswordReset
     */
    protected function storePasswordReset(): Store\PasswordReset
    {
        return $this->factory(Store\PasswordReset::class);
    }

    /**
     * @return \App\Domains\User\Store\Profile
     */
    protected function storeProfile(): Store\Profile
    {
        return $this->factory(Store\Profile::class);
    }

    /**
     * @return \App\Domains\User\Store\Signup
     */
    protected function storeSignup(): Store\Signup
    {
        return $this->factory(Store\Signup::class);
    }
}
