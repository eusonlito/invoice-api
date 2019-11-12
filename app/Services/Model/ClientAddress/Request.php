<?php declare(strict_types=1);

namespace App\Services\Model\ClientAddress;

use Illuminate\Database\Eloquent\Builder;
use App\Models;
use App\Models\ClientAddress as Model;
use App\Services\Model\RequestAbstract;

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
        return $this->fractal('simple', $this->getClientById($client_id)->addresses);
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
        return $this->fractal('simple', $this->getClientById($client_id)->addresses()->enabled()->get());
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
    public function clientCreate(int $client_id): array
    {
        $client = $this->getClientById($client_id);

        return $this->fractal('detail', $this->store($this->validator('clientCreate'))->create($client));
    }

    /**
     * @param int $client_id
     * @param int $id
     *
     * @return array
     */
    public function clientUpdate(int $client_id, int $id): array
    {
        $row = $this->getClientById($client_id)->addresses()->byId($id)->firstOrFail();

        return $this->fractal('detail', $this->store($this->validator('clientUpdate'))->update($row));
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
     * @return \App\Services\Model\ClientAddress\Store
     */
    protected function store(array $data = []): Store
    {
        return $this->store ?? ($this->store = new Store($this->user, $data));
    }
}
