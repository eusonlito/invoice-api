<?php declare(strict_types=1);

namespace App\Domains\InvoiceSerie\Store;

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

        $this->row->delete();

        if ($this->row->css) {
            $this->row::disk()->delete($this->row->css);
        }

        $this->cacheFlush();

        service()->log('invoice_serie', 'delete', $this->user->id);
    }
}
