<?php declare(strict_types=1);

namespace App\Domains\Client\Store;

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

        $this->cacheFlush('Client', 'Invoice');

        service()->log('client', 'delete', $this->user->id);
    }
}
