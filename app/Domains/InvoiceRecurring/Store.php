<?php declare(strict_types=1);

namespace App\Domains\InvoiceRecurring;

use App\Exceptions;
use App\Models\InvoiceRecurring as Model;
use App\Domains\StoreAbstract;

class Store extends StoreAbstract
{
    /**
     * @return \App\Models\InvoiceRecurring
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
     * @param \App\Models\InvoiceRecurring $row
     *
     * @return \App\Models\InvoiceRecurring
     */
    public function update(Model $row): Model
    {
        $this->row = $row;

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

    /**
     * @param \App\Models\InvoiceRecurring $row
     *
     * @return void
     */
    public function delete(Model $row): void
    {
        if ($row->invoices()->count()) {
            throw new Exceptions\NotAllowedException(__('exception.delete-related-invoices'));
        }

        $row->invoices()->update(['recurring_at' => null]);
        $row->delete();

        $this->cacheFlush('InvoiceRecurring', 'Invoice');

        service()->log('invoice_recurring', 'delete', $this->user->id);
    }
}
