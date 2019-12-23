<?php declare(strict_types=1);

namespace App\Domains\Discount\Store;

use App\Exceptions;

class Delete extends StoreAbstract
{
    /**
     * @return void
     */
    public function delete(): void
    {
        if ($this->row->clients()->count()) {
            throw new Exceptions\NotAllowedException(__('exception.delete-related-clients'));
        }

        if ($this->row->invoices()->count()) {
            throw new Exceptions\NotAllowedException(__('exception.delete-related-invoices'));
        }

        $this->row->delete();

        $this->cacheFlush();

        service()->log('discount', 'delete', $this->user->id);
    }
}
