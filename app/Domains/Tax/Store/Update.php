<?php declare(strict_types=1);

namespace App\Domains\Tax\Store;

use App\Models\Tax as Model;

class Update extends StoreAbstract
{
    /**
     * @return \App\Models\Tax
     */
    public function update(): Model
    {
        if ($this->data['default'] && empty($this->row->default)) {
            Model::byCompany($this->user->company)->where('default', true)->update(['default' => false]);
        }

        $this->row->name = $this->data['name'];
        $this->row->value = (float)abs($this->data['value']);
        $this->row->description = $this->data['description'];
        $this->row->default = (bool)$this->data['default'];
        $this->row->enabled = (bool)$this->data['enabled'];

        $this->row->save();

        $this->cacheFlush();

        service()->log('tax', 'update', $this->user->id, ['tax_id' => $this->row->id]);

        return $this->row;
    }
}
