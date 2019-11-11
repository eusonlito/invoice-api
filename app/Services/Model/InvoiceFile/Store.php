<?php declare(strict_types=1);

namespace App\Services\Model\InvoiceFile;

use Illuminate\Support\Facades\Storage;
use App\Models;
use App\Models\InvoiceFile as Model;
use App\Services;
use App\Services\Model\StoreAbstract;

class Store extends StoreAbstract
{
    /**
     * @param \App\Models\Invoice $invoice
     *
     * @return \App\Models\InvoiceFile
     */
    public function create(Models\Invoice $invoice): Model
    {
        $row = new Model([
            'invoice_id' => $invoice->id,
            'company_id' => $this->user->company_id,
            'user_id' => $this->user->id,
        ]);

        return $this->update($row);
    }

    /**
     * @param \App\Models\InvoiceFile $row
     *
     * @return \App\Models\InvoiceFile
     */
    public function update(Model $row): Model
    {
        if (empty($this->data['main'])) {
            StoreUpload::file($row, $this->data['file']);
        } else {
            StoreGenerator::generate($row);
        }

        $this->cacheFlush('Invoice');

        service()->log('invoice_file', 'update', $this->user->id, ['invoice_file_id' => $row->id]);

        return $row;
    }

    /**
     * @param \App\Models\InvoiceFile $row
     *
     * @return void
     */
    public function delete(Model $row)
    {
        $row->delete();

        $this->cacheFlush('Invoice');

        service()->log('invoice_file', 'delete', $this->user->id);
    }
}
