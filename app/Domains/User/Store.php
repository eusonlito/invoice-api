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
        return $this->storeAuth()->byCredentials();
    }

    /**
     * @return \App\Models\User
     */
    public function authUser(): Model
    {
        return $this->storeAuth()->byUser();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return ?\App\Models\User
     */
    public function authRefresh(Request $request): ?Model
    {
        return $this->storeAuth()->refresh($request);
    }

    /**
     * @return ?string
     */
    public function authToken(): ?string
    {
        return $this->storeAuth()->token();
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
        return $this->storeSignup()->start();
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
        return $this->storeConfirm()->start();
    }

    /**
     * @param string $hash
     *
     * @return \App\Models\User
     */
    public function confirmFinish(string $hash): Model
    {
        return $this->storeConfirm()->finish($hash);
    }

    /**
     * @return \App\Models\User
     */
    public function updateProfile(): Model
    {
        return $this->storeProfile()->profile();
    }

    /**
     * @return \App\Models\User
     */
    public function updatePassword(): Model
    {
        return $this->storeProfile()->password();
    }

    /**
     * @return ?\App\Models\User
     */
    public function passwordResetStart(): ?Model
    {
        return $this->storePasswordReset()->start();
    }

    /**
     * @param string $hash
     *
     * @return \App\Models\User
     */
    public function passwordResetFinish(string $hash): Model
    {
        return $this->storePasswordReset()->finish($hash);
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
