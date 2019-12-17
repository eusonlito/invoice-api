<?php declare(strict_types=1);

namespace App\Domains\User;

use Illuminate\Database\Eloquent\Builder;
use App\Models\User as Model;
use App\Domains\RequestAbstract;

class Request extends RequestAbstract
{
    /**
     * @const string
     */
    protected const FRACTAL = Fractal::class;

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
     * @return array
     */
    public function detail(): array
    {
        return $this->fractal('detail', $this->model()->firstOrFail());
    }

    /**
     * @return array
     */
    public function detailCached(): array
    {
        return $this->cache(__METHOD__, fn () => $this->detail());
    }

    /**
     * @return array
     */
    public function signup(): array
    {
        $user = $this->store(null, $this->validator('signup'))->signup();

        return [
            'user' => $this->fractal('detail', $user),
            'token' => $this->store($user)->authToken()
        ];
    }

    /**
     * @return void
     */
    public function confirmStart(): void
    {
        $this->store(null, $this->validator('confirmStart'))->confirmStart();
    }

    /**
     * @param string $hash
     *
     * @return array
     */
    public function confirmFinish(string $hash): array
    {
        return ['confirmed_at' => $this->store()->confirmFinish($hash)->confirmed_at];
    }

    /**
     * @return array
     */
    public function authCredentials(): array
    {
        $user = $this->store(null, $this->validator('authCredentials'))->authCredentials();

        return [
            'user' => $this->fractal('detail', $user),
            'token' => $this->store($user)->authToken()
        ];
    }

    /**
     * @return array
     */
    public function authRefresh(): array
    {
        return ['token' => $this->store($this->user)->authToken()];
    }

    /**
     * @return void
     */
    public function authLogout(): void
    {
        $this->store($this->user)->authLogout();
    }

    /**
     * @return ?array
     */
    public function passwordResetStart(): ?array
    {
        return $this->fractal('detail', $this->store(null, $this->validator('passwordResetStart'))->passwordResetStart());
    }

    /**
     * @param string $hash
     *
     * @return array
     */
    public function passwordResetFinish(string $hash): array
    {
        return $this->fractal('detail', $this->store(null, $this->validator('passwordResetFinish'))->passwordResetFinish($hash));
    }

    /**
     * @return array
     */
    public function updateProfile(): array
    {
        return $this->fractal('detail', $this->store($this->user, $this->validator('updateProfile'))->updateProfile());
    }

    /**
     * @return array
     */
    public function updatePassword(): array
    {
        return $this->fractal('detail', $this->store($this->user, $this->validator('updatePassword'))->updatePassword());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function model(): Builder
    {
        return Model::byId($this->user->id);
    }
}
