<?php declare(strict_types=1);

namespace App\Domains\Invoice\Service;

use Illuminate\Support\Collection;
use App\Domains\InvoiceFile\Store as InvoiceFileStore;
use App\Models;
use App\Models\Invoice as Model;
use App\Services\Zip\Write;

class Zip
{
    /**
     * @param \Illuminate\Support\Collection $list
     *
     * @return string
     */
    public static function export(Collection $list): string
    {
        $zip = [];

        foreach ($list as $row) {
            $file = static::file($row);

            $zip[$file->name] = $file->file_absolute;
        }

        return Write::fromArray($zip, true);
    }

    /**
     * @param \App\Models\Invoice $row
     *
     * @return \App\Models\InvoiceFile
     */
    protected static function file(Model $row): Models\InvoiceFile
    {
        if ($row->file) {
            return $row->file;
        }

        return (new InvoiceFileStore(null, $row->user, null, ['main' => true]))->create($row);
    }
}
