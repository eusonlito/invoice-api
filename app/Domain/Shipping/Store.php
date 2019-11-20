<?php declare(strict_types=1);

namespace App\Domain\Shipping;

use App\Exceptions;
use App\Models\Shipping as Model;
use App\Domain\StoreAbstract;

class Store extends StoreAbstract
{
    /**
     * @return \App\Models\Shipping
     */
    public function create(): Model
    {
        $row = new Model([
            'company_id' => $this->user->company_id,
            'user_id' => $this->user->id,
        ]);

        return $this->update($row);
    }

    /**
     * @param \App\Models\Shipping $row
     *
     * @return \App\Models\Shipping
     */
    public function update(Model $row): Model
    {
        if ($this->data['default'] && empty($row->default)) {
            Model::byCompany($this->user->company)->where('default', true)->update(['default' => false]);
        }

        $row->name = $this->data['name'];
        $row->value = (float)abs($this->data['value']);
        $row->description = $this->data['description'];
        $row->default = (bool)$this->data['default'];
        $row->enabled = (bool)$this->data['enabled'];

        $row->save();

        $this->cacheFlush('Shipping');

        service()->log('shipping', 'update', $this->user->id, ['shipping_id' => $row->id]);

        return $row;
    }

    /**
     * @param \App\Models\Shipping $row
     *
     * @return void
     */
    public function delete(Model $row): void
    {
        if ($row->clients()->count()) {
            throw new Exceptions\NotAllowedException(__('exception.delete-related-clients'));
        }

        if ($row->invoices()->count()) {
            throw new Exceptions\NotAllowedException(__('exception.delete-related-invoices'));
        }

        $row->delete();

        $this->cacheFlush('Shipping');

        service()->log('shipping', 'delete', $this->user->id);
    }
}
