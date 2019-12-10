<?php declare(strict_types=1);

namespace App\Domains\Invoice;

use Illuminate\Support\Collection;
use App\Domains\InvoiceFile\Store as InvoiceFileStore;
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
            $zip[$row->number.'.pdf'] = static::row($row);
        }

        return Write::fromArray($zip, true);
    }

    /**
     * @param \App\Models\Invoice $row
     *
     * @return string
     */
    protected static function row(Model $row): string
    {
        if ($row->file && ($file = $row->file->file_absolute)) {
            return $file;
        }

        return (new InvoiceFileStore($row->user, ['main' => true]))
            ->create($row)
            ->file_absolute;
    }
}
