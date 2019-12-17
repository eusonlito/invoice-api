<?php declare(strict_types=1);

namespace App\Domains\Product\Store;

class Delete extends StoreAbstract
{
    /**
     * @return void
     */
    public function delete(): void
    {
        $this->row->delete();

        $this->cacheFlush('Product', 'Invoice');

        service()->log('product', 'delete', $this->user->id);
    }
}
