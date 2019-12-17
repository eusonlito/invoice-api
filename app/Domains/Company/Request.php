<?php declare(strict_types=1);

namespace App\Domains\Company;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Company as Model;
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
    public function create(): array
    {
        return $this->fractal('detail', $this->store(null, $this->validator('create'))->create());
    }

    /**
     * @return array
     */
    public function update(): array
    {
        return $this->fractal('detail', $this->store($this->model()->firstOrFail(), $this->validator('update'))->update());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function model(): Builder
    {
        return Model::byId((int)$this->user->company_id);
    }
}
