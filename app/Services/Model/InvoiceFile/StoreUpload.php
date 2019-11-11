<?php declare(strict_types=1);

namespace App\Services\Model\InvoiceFile;

use Illuminate\Support\Facades\Storage;
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
        [$ext, $name] = explode('.', strrev($upload->getClientOriginalName()), 2);

        $path = 'invoice-file/file';
        $name = preg_replace('/[^a-zA-Z0-9_-]+/', '-', microtime(true).'-'.strtolower($name));
        $name = $row->invoice->id.'-'.$name.'.'.strtolower($ext);

        Storage::disk('private')->putFileAs($path, $upload, $name);

        return $path.'/'.$name;
    }
}
