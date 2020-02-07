<?php declare(strict_types=1);

namespace App\Domains\InvoiceItem;

use App\Models;
use App\Models\InvoiceItem as Model;

class Store extends Store\StoreAbstract
{
    /**
     * @param \App\Models\Invoice $invoice
     *
     * @return \App\Models\InvoiceItem
     */
    public function create(Models\Invoice $invoice): Model
    {
        return $this->factory(Store\Create::class)->create($invoice);
    }

    /**
     * @return \App\Models\InvoiceItem
     */
    public function update(): Model
    {
        return $this->factory(Store\Update::class)->update();
    }
}
