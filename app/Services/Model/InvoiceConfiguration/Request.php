<?php declare(strict_types=1);

namespace App\Services\Model\InvoiceConfiguration;

use Illuminate\Database\Eloquent\Builder;
use App\Models\InvoiceConfiguration as Model;
use App\Services\Model\RequestAbstract;

class Request extends RequestAbstract
{
    /**
     * @return array
     */
    public function index(): array
    {
        return $this->model()->where('public', true)->pluck('value', 'key')->toArray();
    }

    /**
     * @return array
     */
    public function indexCached(): array
    {
        return $this->cache(__METHOD__, fn () => $this->index());
    }

    /**
     * @return array
     */
    public function update(): array
    {
        return $this->store($this->request->input())->update()->toArray();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function model(): Builder
    {
        return Model::list();
    }

    /**
     * @param array $data = []
     *
     * @return \App\Services\Model\InvoiceConfiguration\Store
     */
    protected function store(array $data = []): Store
    {
        return $this->store ?? ($this->store = new Store($this->user, $data));
    }
}
