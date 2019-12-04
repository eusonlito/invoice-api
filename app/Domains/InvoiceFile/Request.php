<?php declare(strict_types=1);

namespace App\Domains\InvoiceFile;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models;
use App\Models\InvoiceFile as Model;
use App\Domains\RequestAbstract;

class Request extends RequestAbstract
{
    /**
     * @param int $id
     *
     * @return array
     */
    public function detail(int $id): array
    {
        return $this->fractal('detail', $this->modelDetailById($id));
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function detailCached(int $id): array
    {
        return $this->cache(__METHOD__, fn () => $this->detail($id));
    }

    /**
     * @param int $invoice_id
     *
     * @return array
     */
    public function invoice(int $invoice_id): array
    {
        return $this->fractal('detail', $this->modelByInvoiceId($invoice_id)->get());
    }

    /**
     * @param int $invoice_id
     *
     * @return array
     */
    public function invoiceCached(int $invoice_id): array
    {
        return $this->cache(__METHOD__, fn () => $this->invoice($invoice_id));
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
            $invoice->file = $this->store(['main' => true])->create($invoice);
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
        return $this->modelById($id);
    }

    /**
     * @param int $invoice_id
     *
     * @return array
     */
    public function invoiceCreate(int $invoice_id): array
    {
        return $this->fractal('detail', $this->store($this->validator('create'))->create($this->getInvoiceById($invoice_id)));
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function delete(int $id): void
    {
        $this->store()->delete($this->modelById($id));
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
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function model(): Builder
    {
        return Model::byCompany($this->user->company);
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

    /**
     * @param string $name
     * @param mixed $data
     *
     * @return array
     */
    protected function fractal(string $name, $data): array
    {
        return Fractal::transform($name, $data);
    }

    /**
     * @param string $name
     *
     * @return array
     */
    protected function validator(string $name): array
    {
        return Validator::validate($name, $this->request->all());
    }

    /**
     * @param array $data = []
     *
     * @return \App\Domains\InvoiceFile\Store
     */
    protected function store(array $data = []): Store
    {
        return $this->store ?? ($this->store = new Store($this->user, $data));
    }
}
