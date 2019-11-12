<?php declare(strict_types=1);

namespace App\Services\Model\Tax;

use App\Models\Tax as Model;
use App\Services\Model\StoreAbstract;

class Store extends StoreAbstract
{
    /**
     * @return \App\Models\Tax
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
     * @param \App\Models\Tax $row
     *
     * @return \App\Models\Tax
     */
    public function update(Model $row): Model
    {
        if ($this->data['default'] && empty($row->default)) {
            Model::where('default', true)->update(['default' => false]);
        }

        $row->name = $this->data['name'];
        $row->value = (float)abs($this->data['value']);
        $row->description = $this->data['description'];
        $row->default = (bool)$this->data['default'];
        $row->enabled = (bool)$this->data['enabled'];

        $row->save();

        $this->cacheFlush('Tax');

        service()->log('tax', 'update', $this->user->id, ['tax_id' => $row->id]);

        return $row;
    }
}
