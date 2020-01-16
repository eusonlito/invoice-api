<?php declare(strict_types=1);

namespace App\Domains\Company;

use Illuminate\Database\Eloquent\Builder;
use App\Domains\RepositoryAbstract;
use App\Models\Company as Model;

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
     * @return \App\Models\Company
     */
    public function detail(): Model
    {
        return $this->model()->firstOrFail();
    }

    /**
     * @return \App\Models\Company
     */
    public function create(): Model
    {
        return $this->store(null, $this->validator('create'))->create();
    }

    /**
     * @return \App\Models\Company
     */
    public function update(): Model
    {
        return $this->store($this->model()->firstOrFail(), $this->validator('update'))->update();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function model(): Builder
    {
        return Model::byId((int)$this->user->company_id);
    }
}
