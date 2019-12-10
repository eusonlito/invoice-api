<?php declare(strict_types=1);

namespace App\Domains\InvoiceFile;

use App\Domains\InvoiceSerie\StoreCss;
use App\Models;
use App\Models\InvoiceFile as Model;
use App\Services\Pdf\Pdf;
use App\Services\Sign\SignFactory;

class StoreGenerator
{
    /**
     * @param \App\Models\InvoiceFile $row
     *
     * @return \App\Models\InvoiceFile
     */
    public static function generate(Model $row): Model
    {
        $row->name = str_slug($row->invoice->number.'-'.$row->invoice->clientAddressBilling->name).'.pdf';
        $row->file = static::save($row->invoice);
        $row->main = true;

        static::sign($row);

        $row->save();

        return $row;
    }

    /**
     * @param \App\Models\InvoiceFile $row
     *
     * @return string
     */
    public static function download(Model $row): string
    {
        $disk = $row::disk();

        if ($disk->exists($row->file) === false) {
            static::generate($row);
        }

        return $disk->path($row->file);
    }

    /**
     * @param \App\Models\Invoice $invoice
     *
     * @return string
     */
    protected static function html(Models\Invoice $invoice): string
    {
        return (string)view('pdf.pages.invoice.detail', [
            'css' => StoreCss::get($invoice->serie),
            'invoice' => $invoice
        ]);
    }

    /**
     * @param \App\Models\Invoice $invoice
     *
     * @return string
     */
    protected static function save(Models\Invoice $invoice): string
    {
        $name = $invoice->id.'-'.str_replace(['.', ' '], ['', '-'], microtime()).'.pdf';

        return Pdf::save(static::html($invoice), 'invoice-file/file/'.$name);
    }

    /**
     * @param \App\Models\InvoiceFile $row
     *
     * @return \App\Models\InvoiceFile
     */
    protected static function sign(Model $row): Model
    {
        $serie = $row->invoice->serie;

        if (empty($serie->certificate_file)) {
            return $row;
        }

        $path = $row::disk()->path($row->file);
        $certificate = $serie::disk()->path($serie->certificate_file);
        $password = $serie->certificate_password ? decrypt($serie->certificate_password) : '';

        SignFactory::get()->sign($path, $certificate, $password);

        $row->name = preg_replace('/.pdf$/', '.firm.pdf', $row->name);

        return $row;
    }
}
