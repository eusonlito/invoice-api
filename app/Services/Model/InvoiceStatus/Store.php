<?php declare(strict_types=1);

namespace App\Services\Model\InvoiceStatus;

use App\Models\InvoiceStatus as Model;
use App\Services\Model\StoreAbstract;

class Store extends StoreAbstract
{
    /**
     * @return \App\Models\InvoiceStatus
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
     * @param \App\Models\InvoiceStatus $row
     *
     * @return \App\Models\InvoiceStatus
     */
    public function update(Model $row): Model
    {
        if ($this->data['default'] && empty($row->default)) {
            Model::where('default', true)->update(['default' => false]);
        }

        if ($this->data['paid'] && empty($row->paid)) {
            Model::where('paid', true)->update(['paid' => false]);
        }

        $row->name = $this->data['name'];
        $row->order = abs($this->data['order']);
        $row->paid = (bool)$this->data['paid'];
        $row->default = (bool)$this->data['default'];
        $row->enabled = (bool)$this->data['enabled'];

        $row->save();

        $this->cacheFlush('InvoiceStatus');

        service()->log('invoice_status', 'update', $this->user->id, ['invoice_status_id' => $row->id]);

        return $row;
    }
}
