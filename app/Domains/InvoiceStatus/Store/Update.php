<?php declare(strict_types=1);

namespace App\Domains\InvoiceStatus\Store;

use App\Models\InvoiceStatus as Model;

class Update extends StoreAbstract
{
    /**
     * @return \App\Models\InvoiceStatus
     */
    public function update(): Model
    {
        if ($this->data['default'] && empty($this->row->default)) {
            Model::byCompany($this->user->company)->where('default', true)->update(['default' => false]);
        }

        if ($this->data['paid'] && empty($this->row->paid)) {
            Model::byCompany($this->user->company)->where('paid', true)->update(['paid' => false]);
        }

        $this->row->name = $this->data['name'];
        $this->row->order = abs($this->data['order']);
        $this->row->paid = (bool)$this->data['paid'];
        $this->row->default = (bool)$this->data['default'];
        $this->row->enabled = (bool)$this->data['enabled'];

        $this->row->save();

        $this->cacheFlush('InvoiceStatus', 'Invoice');

        service()->log('invoice_status', 'update', $this->user->id, ['invoice_status_id' => $this->row->id]);

        return $this->row;
    }
}
