<?php declare(strict_types=1);

namespace App\Domain\User;

use App\Models\User as Model;
use App\Domain\StoreAbstract;

class Store extends StoreAbstract
{
    /**
     * @return \App\Models\User
     */
    public function authCredentials(): Model
    {
        return $this->cacheFlushResponse('User', StoreAuth::byCredentials($this->data));
    }

    /**
     * @return string
     */
    public function authToken(): string
    {
        return $this->cacheFlushResponse('User', StoreAuth::token());
    }

    /**
     * @return void
     */
    public function authLogout(): void
    {
        StoreAuth::logout();
    }

    /**
     * @return \App\Models\User
     */
    public function signup(): Model
    {
        return $this->cacheFlushResponse('User', StoreSignup::start($this->data));
    }

    /**
     * @return \App\Models\User
     */
    public function confirmStart(): Model
    {
        return $this->cacheFlushResponse('User', StoreConfirm::start($this->data['user']));
    }

    /**
     * @param string $hash
     *
     * @return \App\Models\User
     */
    public function confirmFinish(string $hash): Model
    {
        return $this->cacheFlushResponse('User', StoreConfirm::finish($hash));
    }

    /**
     * @return \App\Models\User
     */
    public function updateProfile(): Model
    {
        return $this->cacheFlushResponse('User', StoreProfile::profile($this->user, $this->data));
    }

    /**
     * @return \App\Models\User
     */
    public function updatePassword(): Model
    {
        return $this->cacheFlushResponse('User', StoreProfile::password($this->user, $this->data['password']));
    }

    /**
     * @return ?\App\Models\User
     */
    public function passwordResetStart(): ?Model
    {
        return $this->cacheFlushResponse('User', StorePasswordReset::start($this->data['user']));
    }

    /**
     * @param string $hash
     *
     * @return \App\Models\User
     */
    public function passwordResetFinish(string $hash): Model
    {
        return $this->cacheFlushResponse('User', StorePasswordReset::finish($hash, $this->data['password']));
    }
}
