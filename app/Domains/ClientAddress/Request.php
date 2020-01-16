<?php declare(strict_types=1);

namespace App\Domains\ClientAddress;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use App\Domains\RequestAbstract;
use App\Models;
use App\Models\ClientAddress as Model;

class Request extends RequestAbstract
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
     * @return \Illuminate\Support\Collection
     */
    public function enabled(): Collection
    {
        return $this->model()->enabled()->list()->get();
    }

    /**
     * @param int $client_id
     *
     * @return \Illuminate\Support\Collection
     */
    public function client(int $client_id): Collection
    {
        return $this->modelByClientId($client_id)->get();
    }

    /**
     * @param int $client_id
     *
     * @return \Illuminate\Support\Collection
     */
    public function clientEnabled(int $client_id): Collection
    {
        return $this->modelByClientId($client_id)->enabled()->get();
    }

    /**
     * @param int $client_id
     *
     * @return \App\Models\ClientAddress
     */
    public function create(int $client_id): Model
    {
        return $this->store(null, $this->validator('create'))->create($this->getClientById($client_id));
    }

    /**
     * @param int $id
     *
     * @return \App\Models\ClientAddress
     */
    public function update(int $id): Model
    {
        return $this->store($this->modelDetailById($id), $this->validator('update'))->update();
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
