<?php declare(strict_types=1);

namespace App\Domains\InvoiceItem\Store;

use App\Domains\InvoiceItem\Store;
use App\Models;
use App\Models\InvoiceItem as Model;

class Create extends StoreAbstract
{
    /**
     * @param \App\Models\Invoice $invoice
     *
     * @return \App\Models\InvoiceItem
     */
    public function create(Models\Invoice $invoice): Model
    {
        $this->row = new Model([
            'company_id' => $invoice->company_id,
            'invoice_id' => $invoice->id,
            'user_id' => $invoice->user_id,
        ]);

        return $this->factory(Store::class)->update();
    }
}
