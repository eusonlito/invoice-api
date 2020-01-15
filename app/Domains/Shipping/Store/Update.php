<?php declare(strict_types=1);

namespace App\Domains\Shipping\Store;

use App\Models\Shipping as Model;

class Update extends StoreAbstract
{
    /**
     * @return \App\Models\Shipping
     */
    public function update(): Model
    {
        if ($this->data['default'] && empty($this->row->default)) {
            Model::byCompany($this->user->company)->where('default', true)->update(['default' => false]);
        }

        $this->row->name = $this->data['name'];
        $this->row->subtotal = (float)abs($this->data['subtotal']);
        $this->row->tax_percent = (float)abs($this->data['tax_percent']);
        $this->row->tax_amount = $this->row->subtotal * $this->row->tax_percent / 100;
        $this->row->value = $this->row->subtotal + $this->row->tax_amount;
        $this->row->description = $this->data['description'];
        $this->row->default = (bool)$this->data['default'];
        $this->row->enabled = (bool)$this->data['enabled'];

        $this->row->save();

        $this->cacheFlush();

        service()->log('shipping', 'update', $this->user->id, ['shipping_id' => $this->row->id]);

        return $this->row;
    }
}
