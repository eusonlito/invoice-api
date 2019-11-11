<?php declare(strict_types=1);

namespace App\Services\Model\Company;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Company as Model;
use App\Services\Model\RequestAbstract;

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
        return $this->cache(__METHOD__, fn() => $this->detail());
    }

    /**
     * @return array
     */
    public function update(): array
    {
        return $this->fractal('detail', $this->store($this->validator('update'))->update());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function model(): Builder
    {
        return Model::byId((int)$this->user->company_id);
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
     * @return \App\Services\Model\Company\Store
     */
    protected function store(array $data = []): Store
    {
        return $this->store ?? ($this->store = new Store($this->user, $data));
    }
}
