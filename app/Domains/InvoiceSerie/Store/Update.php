<?php declare(strict_types=1);

namespace App\Domains\InvoiceSerie\Store;

use App\Domains\InvoiceSerie\Store;
use App\Models\InvoiceSerie as Model;

class Update extends StoreAbstract
{
    /**
     * @return \App\Models\InvoiceSerie
     */
    public function update(): Model
    {
        if ($this->data['default'] && empty($this->row->default)) {
            Model::byCompany($this->user->company)->where('default', true)->update(['default' => false]);
        }

        $this->row->name = $this->data['name'];
        $this->row->number_prefix = $this->data['number_prefix'];
        $this->row->number_fill = (int)$this->data['number_fill'];
        $this->row->number_next = (int)$this->data['number_next'];
        $this->row->default = (bool)$this->data['default'];
        $this->row->enabled = (bool)$this->data['enabled'];

        $this->factory(Store::class)->certificate();

        $this->row->save();

        $this->cacheFlush();

        service()->log('invoice_serie', 'update', $this->user->id, ['invoice_serie_id' => $this->row->id]);

        return $this->row;
    }
}
