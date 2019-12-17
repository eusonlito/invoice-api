<?php declare(strict_types=1);

namespace App\Domains\InvoiceRecurring\Store;

use App\Models\InvoiceRecurring as Model;

class Update extends StoreAbstract
{
    /**
     * @return \App\Models\InvoiceRecurring
     */
    public function update(): Model
    {
        $this->row->name = $this->data['name'];
        $this->row->every = $this->data['every'];
        $this->row->enabled = (bool)$this->data['enabled'];

        $this->row->save();

        $this->invoices();

        $this->cacheFlush('InvoiceRecurring', 'Invoice');

        service()->log('invoice_recurring', 'update', $this->user->id, ['invoice_recurring_id' => $this->row->id]);

        return $this->row;
    }

    /**
     * @return void
     */
    protected function invoices()
    {
        $this->row->invoices()->update([
            'recurring_at' => Model::dateAdd('date_at', 1, $this->row->every)
        ]);
    }
}
