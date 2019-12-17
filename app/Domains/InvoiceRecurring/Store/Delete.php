<?php declare(strict_types=1);

namespace App\Domains\InvoiceRecurring\Store;

use App\Exceptions\NotAllowedException;

class Delete extends StoreAbstract
{
    /**
     * @return void
     */
    public function delete(): void
    {
        if ($this->row->invoices()->count()) {
            throw new NotAllowedException(__('exception.delete-related-invoices'));
        }

        $this->row->invoices()->update(['recurring_at' => null]);
        $this->row->delete();

        $this->cacheFlush('InvoiceRecurring', 'Invoice');

        service()->log('invoice_recurring', 'delete', $this->user->id);
    }
}
