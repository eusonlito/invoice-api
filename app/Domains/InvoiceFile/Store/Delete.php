<?php declare(strict_types=1);

namespace App\Domains\InvoiceFile\Store;

class Delete extends StoreAbstract
{
    /**
     * @return void
     */
    public function delete()
    {
        $this->row->delete();

        $this->row::disk()->delete($this->row->file);

        $this->cacheFlush('InvoiceFile', 'Invoice');

        service()->log('invoice_file', 'delete', $this->user->id);
    }
}
