<?php declare(strict_types=1);

namespace App\Domains\Invoice\Store;

use App\Domains\InvoiceFile\Store as InvoiceFileStore;

class Delete extends StoreAbstract
{
    /**
     * @return void
     */
    public function delete(): void
    {
        foreach ($this->row->files as $each) {
            (new InvoiceFileStore($this->user, $each))->delete();
        }

        $this->row->delete();

        $this->cacheFlush('Invoice');

        service()->log('invoice', 'delete', $this->user->id);
    }
}
