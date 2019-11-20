<?php declare(strict_types=1);

namespace App\Domain\InvoiceFile;

use Illuminate\Http\UploadedFile;
use App\Models\InvoiceFile as Model;

class StoreUpload
{
    /**
     * @param \App\Models\InvoiceFile $row
     * @param \Illuminate\Http\UploadedFile $upload
     *
     * @return \App\Models\InvoiceFile
     */
    public static function file(Model $row, UploadedFile $upload): Model
    {
        $row->name = $upload->getClientOriginalName();
        $row->file = static::store($row, $upload);
        $row->main = false;

        $row->save();

        return $row;
    }

    /**
     * @param \App\Models\InvoiceFile $row
     * @param \Illuminate\Http\UploadedFile $upload
     *
     * @return string
     */
    protected static function store(Model $row, UploadedFile $upload): string
    {
        $name = explode('.', $upload->getClientOriginalName());
        $ext = array_pop($name);

        $path = 'invoice-file/file';
        $name = str_slug(microtime().'-'.implode('-', $name), '-');
        $name = $row->invoice->id.'-'.$name.'.'.strtolower($ext);

        Model::disk()->putFileAs($path, $upload, $name);

        return $path.'/'.$name;
    }
}
