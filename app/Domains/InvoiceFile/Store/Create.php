<?php declare(strict_types=1);

namespace App\Domains\InvoiceFile\Store;

use App\Domains\InvoiceFile\Store;
use App\Models;
use App\Models\InvoiceFile as Model;

class Create extends StoreAbstract
{
    /**
     * @param \App\Models\Invoice $invoice
     *
     * @return \App\Models\InvoiceFile
     */
    public function create(Models\Invoice $invoice): Model
    {
        $this->row = new Model([
            'invoice_id' => $invoice->id,
            'company_id' => $this->user->company_id,
            'user_id' => $this->user->id,
        ]);

        $this->row->setRelation('invoice', $invoice);

        return $this->factory(Store::class)->update();
    }
}
