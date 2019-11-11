<?php declare(strict_types=1);

namespace App\Services\Model\Shipping;

use App\Models;
use App\Services\Model\StoreAbstract;

class Store extends StoreAbstract
{
    /**
     * @return \App\Models\Shipping
     */
    public function create(): Models\Shipping
    {
        $row = new Models\Shipping([
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
    public function update(Models\Shipping $row): Models\Shipping
    {
        $row->name = $this->data['name'];
        $row->value = (float)abs($this->data['value']);
        $row->description = $this->data['description'];
        $row->enabled = (bool)$this->data['enabled'];

        $row->save();

        $this->cacheFlush('Shipping');

        service()->log('shipping', 'update', $this->user->id, ['shipping_id' => $row->id]);

        return $row;
    }
}
