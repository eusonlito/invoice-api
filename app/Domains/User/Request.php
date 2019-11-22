<?php declare(strict_types=1);

namespace App\Domains\User;

use Illuminate\Database\Eloquent\Builder;
use App\Models\User as Model;
use App\Domains\RequestAbstract;

class Request extends RequestAbstract
{
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
        $user = $this->store($this->validator('signup'))->signup();

        return [
            'user' => $this->fractal('detail', $user),
            'token' => $this->store()->authToken()
        ];
    }

    /**
     * @return void
     */
    public function confirmStart(): void
    {
        $this->store($this->validator('confirmStart'))->confirmStart();
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
        $user = $this->store($this->validator('authCredentials'))->authCredentials();

        return [
            'user' => $this->fractal('detail', $user),
            'token' => $this->store()->authToken()
        ];
    }

    /**
     * @return array
     */
    public function authRefresh(): array
    {
        return ['token' => StoreAuth::token()];
    }

    /**
     * @return void
     */
    public function authLogout(): void
    {
        $this->store()->authLogout();
    }

    /**
     * @return ?array
     */
    public function passwordResetStart(): ?array
    {
        return $this->fractal('detail', $this->store($this->validator('passwordResetStart'))->passwordResetStart());
    }

    /**
     * @param string $hash
     *
     * @return array
     */
    public function passwordResetFinish(string $hash): array
    {
        return $this->fractal('detail', $this->store($this->validator('passwordResetFinish'))->passwordResetFinish($hash));
    }

    /**
     * @return array
     */
    public function updateProfile(): array
    {
        return $this->fractal('detail', $this->store($this->validator('updateProfile'))->updateProfile());
    }

    /**
     * @return array
     */
    public function updatePassword(): array
    {
        return $this->fractal('detail', $this->store($this->validator('updatePassword'))->updatePassword());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function model(): Builder
    {
        return Model::byId($this->user->id);
    }

    /**
     * @param string $name
     * @param mixed $data
     *
     * @return ?array
     */
    protected function fractal(string $name, $data): ?array
    {
        return Fractal::transform($name, $data);
    }

    /**
     * @param string $name
     *
     * @return array
     */
    protected function validator(string $name): array
    {
        return Validator::validate($name, $this->request->all());
    }

    /**
     * @param array $data = []
     *
     * @return \App\Domains\User\Store
     */
    protected function store(array $data = []): Store
    {
        return $this->store ?? ($this->store = new Store($this->user, $data));
    }
}
