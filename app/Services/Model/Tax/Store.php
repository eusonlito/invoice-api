<?php declare(strict_types=1);

namespace App\Services\Model\Tax;

use App\Models;
use App\Services\Model\StoreAbstract;

class Store extends StoreAbstract
{
    /**
     * @return \App\Models\Tax
     */
    public function create(): Models\Tax
    {
        $row = new Models\Tax([
            'company_id' => $this->user->company_id,
            'user_id' => $this->user->id,
        ]);

        return $this->update($row);
    }

    /**
     * @param \App\Models\Tax $row
     *
     * @return \App\Models\Tax
     */
    public function update(Models\Tax $row): Models\Tax
    {
        $row->name = $this->data['name'];
        $row->value = (float)abs($this->data['value']);
        $row->description = $this->data['description'];
        $row->enabled = (bool)$this->data['enabled'];

        $row->save();

        $this->cacheFlush('Tax');

        service()->log('tax', 'update', $this->user->id, ['tax_id' => $row->id]);

        return $row;
    }
}
