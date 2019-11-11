<?php declare(strict_types=1);

namespace App\Services\Model\InvoiceStatus;

use App\Models;
use App\Services\Model\StoreAbstract;

class Store extends StoreAbstract
{
    /**
     * @return \App\Models\InvoiceStatus
     */
    public function create(): Models\InvoiceStatus
    {
        $row = new Models\InvoiceStatus([
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
    public function update(Models\InvoiceStatus $row): Models\InvoiceStatus
    {
        if ($this->data['paid'] && empty($row->paid)) {
            Models\InvoiceStatus::where('paid', true)->update(['paid' => false]);
        }

        $row->name = $this->data['name'];
        $row->order = abs($this->data['order']);
        $row->paid = (bool)$this->data['paid'];
        $row->enabled = (bool)$this->data['enabled'];

        $row->save();

        $this->cacheFlush('InvoiceStatus');

        service()->log('invoice_status', 'update', $this->user->id, ['invoice_status_id' => $row->id]);

        return $row;
    }
}