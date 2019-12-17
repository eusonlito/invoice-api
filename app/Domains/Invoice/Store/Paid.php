<?php declare(strict_types=1);

namespace App\Domains\Invoice\Store;

use App\Models;
use App\Models\Invoice as Model;

class Paid extends StoreAbstract
{
    /**
     * @return \App\Models\Invoice
     */
    public function paid(): Model
    {
        $this->row->paid_at = date('Y-m-d');
        $this->row->amount_paid = $this->row->amount_total;
        $this->row->amount_due = 0;

        $status = Models\InvoiceStatus::select('id')
            ->byCompany($this->user->company)
            ->where('paid', true)
            ->first();

        if ($status) {
            $this->row->invoice_status_id = $status->id;
            $this->row->load(['status']);
        }

        $this->row->save();

        $this->cacheFlush('Invoice');

        service()->log('invoice', 'paid', $this->user->id, ['invoice_id' => $this->row->id]);

        return $this->row;
    }
}
