<?php declare(strict_types=1);

namespace App\Domain\InvoiceFile;

use App\Domain\InvoiceSerie\StoreCss;
use App\Models;
use App\Models\InvoiceFile as Model;
use App\Services\Pdf\Pdf;

class StoreGenerator
{
    /**
     * @param \App\Models\InvoiceFile $row
     *
     * @return \App\Models\InvoiceFile
     */
    public static function generate(Model $row): Model
    {
        $row->name = $row->invoice->number.'.pdf';
        $row->file = static::save($row->invoice);
        $row->main = true;

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
        $disk = Model::disk();

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
    public static function html(Models\Invoice $invoice): string
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
    public static function save(Models\Invoice $invoice): string
    {
        $name = $invoice->id.'-'.str_replace(['.', ' '], ['', '-'], microtime()).'.pdf';

        return Pdf::save(static::html($invoice), 'invoice-file/file/'.$name);
    }
}
