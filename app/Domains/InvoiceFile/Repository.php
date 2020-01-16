<?php declare(strict_types=1);

namespace App\Domains\InvoiceFile;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use App\Domains\RepositoryAbstract;
use App\Models;
use App\Models\InvoiceFile as Model;

class Repository extends RepositoryAbstract
{
    /**
     * @const string
     */
    protected const MODEL = Model::class;

    /**
     * @const string
     */
    protected const STORE = Store::class;

    /**
     * @const string
     */
    protected const VALIDATOR = Validator::class;

    /**
     * @param int $id
     *
     * @return \App\Models\InvoiceFile
     */
    public function detail(int $id): Model
    {
        return $this->modelDetailById($id);
    }

    /**
     * @param int $invoice_id
     *
     * @return \Illuminate\Support\Collection
     */
    public function invoice(int $invoice_id): Collection
    {
        return $this->modelByInvoiceId($invoice_id)->get();
    }

    /**
     * @param int $invoice_id
     *
     * @return \App\Models\InvoiceFile
     */
    public function invoiceMain(int $invoice_id): Model
    {
        $invoice = $this->getInvoiceById($invoice_id);

        if (empty($invoice->file)) {
            $invoice->file = $this->store(null, ['main' => true])->create($invoice);
        }

        return $invoice->file;
    }

    /**
     * @param int $id
     *
     * @return \App\Models\InvoiceFile
     */
    public function download(int $id): Model
    {
        return $this->store($this->modelById($id))->download();
    }

    /**
     * @param int $invoice_id
     *
     * @return \App\Models\InvoiceFile
     */
    public function invoiceCreate(int $invoice_id): Model
    {
        return $this->store(null, $this->validator('create'))->create($this->getInvoiceById($invoice_id));
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function delete(int $id): void
    {
        $this->store($this->modelById($id))->delete();
    }

    /**
     * @param int $id
     *
     * @return \App\Models\Invoice
     */
    protected function getInvoiceById(int $id): Models\Invoice
    {
        return Models\Invoice::byCompany($this->user->company)->byId($id)->firstOrFail();
    }

    /**
     * @param int $invoice_id
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    protected function modelByInvoiceId(int $invoice_id): HasMany
    {
        return $this->getInvoiceById($invoice_id)->files();
    }
}
