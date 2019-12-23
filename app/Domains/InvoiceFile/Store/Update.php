<?php declare(strict_types=1);

namespace App\Domains\InvoiceFile\Store;

use App\Domains\InvoiceFile\Store;
use App\Models\InvoiceFile as Model;

class Update extends StoreAbstract
{
    /**
     * @return \App\Models\InvoiceFile
     */
    public function update(): Model
    {
        $previous = $this->row->file;

        if (empty($this->data['main'])) {
            $this->factory(Store::class)->upload();
        } else {
            $this->factory(Store::class)->generate();
        }

        if ($previous) {
            $this->row::disk()->delete($previous);
        }

        $this->cacheFlush();

        service()->log('invoice_file', 'update', $this->user->id, ['invoice_file_id' => $this->row->id]);

        return $this->row;
    }
}
