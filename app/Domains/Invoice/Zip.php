<?php declare(strict_types=1);

namespace App\Domains\Invoice;

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
        return $row->file ?: (new InvoiceFileStore($row->user, ['main' => true]))->create($row);
    }
}
