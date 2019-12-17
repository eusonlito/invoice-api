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
        return $this->cacheFlushResponse('User', $this->storeAuth()->byCredentials());
    }

    /**
     * @return \App\Models\User
     */
    public function authUser(): Model
    {
        return $this->cacheFlushResponse('User', $this->storeAuth()->byUser());
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Models\User
     */
    public function authRefresh(Request $request): Model
    {
        return $this->cacheFlushResponse('User', $this->storeAuth()->refresh($request));
    }

    /**
     * @return ?string
     */
    public function authToken(): ?string
    {
        return $this->cacheFlushResponse('User', $this->storeAuth()->token());
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
        return $this->cacheFlushResponse('User', $this->storeSignup()->start());
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
        return $this->cacheFlushResponse('User', $this->storeConfirm()->start());
    }

    /**
     * @param string $hash
     *
     * @return \App\Models\User
     */
    public function confirmFinish(string $hash): Model
    {
        return $this->cacheFlushResponse('User', $this->storeConfirm()->finish($hash));
    }

    /**
     * @return \App\Models\User
     */
    public function updateProfile(): Model
    {
        return $this->cacheFlushResponse('User', $this->storeProfile()->profile());
    }

    /**
     * @return \App\Models\User
     */
    public function updatePassword(): Model
    {
        return $this->cacheFlushResponse('User', $this->storeProfile()->password());
    }

    /**
     * @return ?\App\Models\User
     */
    public function passwordResetStart(): ?Model
    {
        return $this->cacheFlushResponse('User', $this->storePasswordReset()->start());
    }

    /**
     * @param string $hash
     *
     * @return \App\Models\User
     */
    public function passwordResetFinish(string $hash): Model
    {
        return $this->cacheFlushResponse('User', $this->storePasswordReset()->finish($hash));
    }

    /**
     * @return \App\Domains\User\Store\Auth
     */
    protected function storeAuth(): Store\Auth
    {
        return new Store\Auth($this->user, $this->row, $this->data);
    }

    /**
     * @return \App\Domains\User\Store\Confirm
     */
    protected function storeConfirm(): Store\Confirm
    {
        return new Store\Confirm($this->user, $this->row, $this->data);
    }

    /**
     * @return \App\Domains\User\Store\PasswordReset
     */
    protected function storePasswordReset(): Store\PasswordReset
    {
        return new Store\PasswordReset($this->user, $this->row, $this->data);
    }

    /**
     * @return \App\Domains\User\Store\Profile
     */
    protected function storeProfile(): Store\Profile
    {
        return new Store\Profile($this->user, $this->row, $this->data);
    }

    /**
     * @return \App\Domains\User\Store\Signup
     */
    protected function storeSignup(): Store\Signup
    {
        return new Store\Signup($this->user, $this->row, $this->data);
    }
}
