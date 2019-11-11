<?php declare(strict_types=1);

namespace App\Services\Model\InvoiceFile;

use Illuminate\Support\Facades\Storage;
use App\Models\InvoiceFile as Model;
use App\Services;

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
        $row->file = static::pdf($row);
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
        $disk = Storage::disk('private');

        if ($disk->exists($row->file) === false) {
            static::generate($row);
        }

        return $disk->path($row->file);
    }

    /**
     * @param \App\Models\InvoiceFile $row
     *
     * @return string
     */
    protected static function pdf(Model $row): string
    {
        $html = (string)view('pdf.pages.invoice.detail', [
            'invoice' => $row->invoice
        ]);

        return Services\Pdf\Pdf::fromHtml($html, 'invoice-file/file/'.$row->invoice->id.'.pdf');
    }
}
