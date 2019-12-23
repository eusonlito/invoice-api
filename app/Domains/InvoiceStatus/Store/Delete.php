<?php declare(strict_types=1);

namespace App\Domains\InvoiceStatus\Store;

use App\Exceptions;

class Delete extends StoreAbstract
{
    /**
     * @return void
     */
    public function delete(): void
    {
        if ($this->row->invoices()->count()) {
            throw new Exceptions\NotAllowedException(__('exception.delete-related-invoices'));
        }

        $this->row->delete();

        $this->cacheFlush();

        service()->log('invoice_status', 'delete', $this->user->id);
    }
}
