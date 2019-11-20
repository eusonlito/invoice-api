<?php declare(strict_types=1);

namespace App\Domain\ClientAddress;

use Illuminate\Database\Eloquent\Builder;
use App\Models;
use App\Models\ClientAddress as Model;
use App\Domain\RequestAbstract;

class Request extends RequestAbstract
{
    /**
     * @return array
     */
    public function enabled(): array
    {
        return $this->fractal('simple', $this->model()->enabled()->list()->get());
    }

    /**
     * @return array
     */
    public function enabledCached(): array
    {
        return $this->cache(__METHOD__, fn () => $this->enabled());
    }

    /**
     * @param int $client_id
     *
     * @return array
     */
    public function client(int $client_id): array
    {
        return $this->fractal('simple', $this->modelByClientId($client_id)->get());
    }

    /**
     * @param int $client_id
     *
     * @return array
     */
    public function clientCached(int $client_id): array
    {
        return $this->cache(__METHOD__, fn () => $this->client($client_id));
    }

    /**
     * @param int $client_id
     *
     * @return array
     */
    public function clientEnabled(int $client_id): array
    {
        return $this->fractal('simple', $this->modelByClientId($client_id)->enabled()->get());
    }

    /**
     * @param int $client_id
     *
     * @return array
     */
    public function clientEnabledCached(int $client_id): array
    {
        return $this->cache(__METHOD__, fn () => $this->clientEnabled($client_id));
    }

    /**
     * @param int $client_id
     *
     * @return array
     */
    public function create(int $client_id): array
    {
        return $this->fractal('detail', $this->store($this->validator('create'))->create($this->getClientById($client_id)));
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function update(int $id): array
    {
        return $this->fractal('detail', $this->store($this->validator('update'))->update($this->modelDetailById($id)));
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function delete(int $id): void
    {
        $this->store()->delete($this->modelDetailById($id));
    }

    /**
     * @param int $id
     *
     * @return \App\Models\Client
     */
    protected function getClientById(int $id): Models\Client
    {
        return Models\Client::byCompany($this->user->company)->byId($id)->firstOrFail();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function model(): Builder
    {
        return Model::byCompany($this->user->company);
    }

    /**
     * @param int $client_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function modelByClientId(int $client_id): Builder
    {
        return $this->model()->where('client_id', $client_id);
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
     * @return \App\Domain\ClientAddress\Store
     */
    protected function store(array $data = []): Store
    {
        return $this->store ?? ($this->store = new Store($this->user, $data));
    }
}
