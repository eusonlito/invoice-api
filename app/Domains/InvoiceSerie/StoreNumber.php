<?php declare(strict_types=1);

namespace App\Domains\InvoiceSerie;

use App\Models;
use App\Models\InvoiceSerie as Model;

class StoreNumber
{
    /**
     * @param \App\Models\InvoiceSerie $row
     *
     * @return \App\Models\InvoiceSerie
     */
    public static function setNext(Model $row): Model
    {
        if ($row->number_prefix) {
            $row->number_next = static::nextWithPrefix($row);
        } else {
            $row->number_next = static::nextWithoutPrefix($row);
        }

        $row->save();

        return $row;
    }

    /**
     * @param \App\Models\InvoiceSerie $row
     *
     * @return int
     */
    protected static function nextWithPrefix(Model $row): int
    {
        $number = Models\Invoice::byCompany($row->company)
            ->where('number', 'LIKE', $row->number_prefix.'%')
            ->where('invoice_serie_id', $row->id)
            ->orderBy('number', 'DESC')
            ->first()
            ->number ?? 0;

        return (int)preg_replace('/^'.preg_quote($row->number_prefix, '/').'/', '', (string)$number) + 1;
    }

    /**
     * @param \App\Models\InvoiceSerie $row
     *
     * @return int
     */
    protected static function nextWithoutPrefix(Model $row): int
    {
        $number = Models\Invoice::byCompany($row->company)
            ->where('invoice_serie_id', $row->id)
            ->orderBy('number', 'DESC')
            ->first()
            ->number ?? 0;

        return $number + 1;
    }
}
