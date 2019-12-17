<?php declare(strict_types=1);

namespace App\Domains\ClientAddress;

use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models;
use App\Models\ClientAddress as Model;
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
        return $this->fractal('detail', $this->store(null, $this->validator('create'))->create($this->getClientById($client_id)));
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function update(int $id): array
    {
        return $this->fractal('detail', $this->store($this->modelDetailById($id), $this->validator('update'))->update());
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function delete(int $id): void
    {
        $this->store($this->modelDetailById($id))->delete();
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
     * @param int $client_id
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    protected function modelByClientId(int $client_id): HasMany
    {
        return $this->getClientById($client_id)->addresses();
    }
}
