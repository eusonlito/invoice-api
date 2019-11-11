<?php declare(strict_types=1);

namespace App\Services\Model\Discount;

use App\Models;
use App\Services\Model\StoreAbstract;

class Store extends StoreAbstract
{
    /**
     * @return \App\Models\Discount
     */
    public function create(): Models\Discount
    {
        $row = new Models\Discount([
            'company_id' => $this->user->company_id,
            'user_id' => $this->user->id,
        ]);

        return $this->update($row);
    }

    /**
     * @param \App\Models\Discount $row
     *
     * @return \App\Models\Discount
     */
    public function update(Models\Discount $row): Models\Discount
    {
        $row->name = $this->data['name'];
        $row->type = $this->data['type'];
        $row->value = (float)abs($this->data['value']);
        $row->description = $this->data['description'];
        $row->enabled = (bool)$this->data['enabled'];

        $row->save();

        $this->cacheFlush('Discount');

        service()->log('discount', 'update', $this->user->id, ['discount_id' => $row->id]);

        return $row;
    }
}
