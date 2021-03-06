<?php declare(strict_types=1);

namespace App\Domains\Client;

use Illuminate\Support\Collection;
use App\Domains\RepositoryAbstract;
use App\Models\Client as Model;

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
     * @return \Illuminate\Support\Collection
     */
    public function index(): Collection
    {
        return $this->model()->list()->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function export(): Collection
    {
        return $this->model()->export()->get();
    }

    /**
     * @param int $id
     *
     * @return \App\Models\Client
     */
    public function detail(int $id): Model
    {
        return $this->modelDetailById($id);
    }

    /**
     * @return \App\Models\Client
     */
    public function create(): Model
    {
        return $this->store(null, $this->validator('create'))->create();
    }

    /**
     * @param int $id
     *
     * @return \App\Models\Client
     */
    public function update(int $id): Model
    {
        return $this->store($this->modelById($id), $this->validator('update'))->update();
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function delete(int $id): void
    {
        $this->store($this->modelById($id))->delete();
    }
}
