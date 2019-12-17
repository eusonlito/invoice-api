<?php declare(strict_types=1);

namespace App\Domains\InvoiceFile\Store;

use App\Domains\InvoiceSerie\Store as InvoiceSerieStore;
use App\Models;
use App\Models\InvoiceFile as Model;
use App\Services\Pdf\Pdf;

class Generate extends StoreAbstract
{
    /**
     * @return \App\Models\InvoiceFile
     */
    public function generate(): Model
    {
        $invoice = $this->row->invoice;

        $this->row->name = str_slug($invoice->number.'-'.$invoice->clientAddressBilling->name).'.pdf';
        $this->row->file = $this->save($invoice);
        $this->row->main = true;

        $this->sign();

        $this->row->save();

        return $this->row;
    }

    /**
     * @return \App\Models\InvoiceFile
     */
    public function download(): Model
    {
        $disk = $this->row::disk();

        if ($disk->exists($this->row->file) === false) {
            $this->generate();
        }

        return $this->row;
    }

    /**
     * @param \App\Models\Invoice $invoice
     *
     * @return string
     */
    protected function html(Models\Invoice $invoice): string
    {
        return (string)view('pdf.pages.invoice.detail', [
            'css' => (new InvoiceSerieStore($this->user, $invoice->serie))->css(),
            'invoice' => $invoice
        ]);
    }

    /**
     * @param \App\Models\Invoice $invoice
     *
     * @return string
     */
    protected function save(Models\Invoice $invoice): string
    {
        $name = $invoice->id.'-'.str_replace(['.', ' '], ['', '-'], microtime()).'.pdf';

        return Pdf::save($this->html($invoice), 'invoice-file/file/'.$name);
    }

    /**
     * @return \App\Models\InvoiceFile
     */
    protected function sign(): Model
    {
        $serie = $this->row->invoice->serie;

        if (empty($serie->certificate_file)) {
            return $this->row;
        }

        $path = $this->row::disk()->path($this->row->file);
        $certificate = $serie::disk()->path($serie->certificate_file);
        $password = $serie->certificate_password ? decrypt($serie->certificate_password) : '';

        SignFactory::get()->sign($path, $certificate, $password);

        $this->row->name = preg_replace('/.pdf$/', '.firm.pdf', $this->row->name);

        return $this->row;
    }
}
