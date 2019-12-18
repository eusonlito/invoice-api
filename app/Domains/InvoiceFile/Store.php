<?php declare(strict_types=1);

namespace App\Domains\InvoiceFile;

use App\Models;
use App\Models\InvoiceFile as Model;

class Store extends Store\StoreAbstract
{
    /**
     * @param \App\Models\Invoice $invoice
     *
     * @return \App\Models\InvoiceFile
     */
    public function create(Models\Invoice $invoice): Model
    {
        return $this->factory(Store\Create::class)->create($invoice);
    }

    /**
     * @return \App\Models\InvoiceFile
     */
    public function update(): Model
    {
        return $this->factory(Store\Update::class)->update();
    }

    /**
     * @return \App\Models\InvoiceFile
     */
    public function generate(): Model
    {
        return $this->factory(Store\Generate::class)->generate();
    }

    /**
     * @return \App\Models\InvoiceFile
     */
    public function upload(): Model
    {
        return $this->factory(Store\Upload::class)->upload();
    }

    /**
     * @return \App\Models\InvoiceFile
     */
    public function download(): Model
    {
        return $this->factory(Store\Generate::class)->download();
    }

    /**
     * @return void
     */
    public function delete(): void
    {
        $this->factory(Store\Delete::class)->delete();
    }
}
