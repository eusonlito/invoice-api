<?php declare(strict_types=1);

namespace App\Domains\User;

use Illuminate\Database\Eloquent\Builder;
use App\Domains\RepositoryAbstract;
use App\Models\User as Model;

class Repository extends RepositoryAbstract
{
    /**
     * @const string
     */
    protected const MODEL = Model::class;

    /**
     * @const string
     */
    protected const STORE = Store::class;

    /**
     * @const string
     */
    protected const VALIDATOR = Validator::class;

    /**
     * @return \App\Models\User
     */
    public function detail(): Model
    {
        return $this->model()->firstOrFail();
    }

    /**
     * @return \App\Models\User
     */
    public function signup(): Model
    {
        return $this->store(null, $this->validator('signup'))->signup();
    }

    /**
     * @return string
     */
    public function authToken(): string
    {
        return (string)$this->store($this->user)->authToken();
    }

    /**
     * @return \App\Models\User
     */
    public function confirmStart(): Model
    {
        return $this->store(null, $this->validator('confirmStart'))->confirmStart();
    }

    /**
     * @param string $hash
     *
     * @return \App\Models\User
     */
    public function confirmFinish(string $hash): Model
    {
        return $this->store()->confirmFinish($hash);
    }

    /**
     * @return \App\Models\User
     */
    public function authCredentials(): Model
    {
        return $this->store(null, $this->validator('authCredentials'))->authCredentials();
    }

    /**
     * @return void
     */
    public function authLogout(): void
    {
        $this->store($this->user)->authLogout();
    }

    /**
     * @return ?\App\Models\User
     */
    public function passwordResetStart(): ?Model
    {
        return $this->store(null, $this->validator('passwordResetStart'))->passwordResetStart();
    }

    /**
     * @param string $hash
     *
     * @return \App\Models\User
     */
    public function passwordResetFinish(string $hash): Model
    {
        return $this->store(null, $this->validator('passwordResetFinish'))->passwordResetFinish($hash);
    }

    /**
     * @return \App\Models\User
     */
    public function updateProfile(): Model
    {
        return $this->store($this->user, $this->validator('updateProfile'))->updateProfile();
    }

    /**
     * @return \App\Models\User
     */
    public function updatePassword(): Model
    {
        return $this->store($this->user, $this->validator('updatePassword'))->updatePassword();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function model(): Builder
    {
        return Model::byId($this->user->id);
    }
}
