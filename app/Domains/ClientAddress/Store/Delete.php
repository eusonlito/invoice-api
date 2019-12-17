<?php declare(strict_types=1);

namespace App\Domains\ClientAddress\Store;

use App\Exceptions;

class Delete extends StoreAbstract
{
    /**
     * @return void
     */
    public function delete(): void
    {
        if ($this->row->invoicesBilling()->count() || $this->row->invoicesShipping()->count()) {
            throw new Exceptions\NotAllowedException(__('exception.delete-related-invoices'));
        }

        $this->row->delete();

        $this->cacheFlush('ClientAddress', 'Invoice');

        service()->log('client_address', 'delete', $this->user->id);
    }
}
