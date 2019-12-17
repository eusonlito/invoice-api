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
        return (new Store\Create($this->user, null, $this->data))->create($invoice);
    }

    /**
     * @return \App\Models\InvoiceFile
     */
    public function update(): Model
    {
        return (new Store\Update($this->user, $this->row, $this->data))->update();
    }

    /**
     * @return \App\Models\InvoiceFile
     */
    public function generate(): Model
    {
        return (new Store\Generate($this->user, $this->row, $this->data))->generate();
    }

    /**
     * @return \App\Models\InvoiceFile
     */
    public function upload(): Model
    {
        return (new Store\Upload($this->user, $this->row, $this->data))->upload();
    }

    /**
     * @return \App\Models\InvoiceFile
     */
    public function download(): Model
    {
        return (new Store\Generate($this->user, $this->row, $this->data))->download();
    }

    /**
     * @return void
     */
    public function delete(): void
    {
        (new Store\Delete($this->user, $this->row))->delete();
    }
}
