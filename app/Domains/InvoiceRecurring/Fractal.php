<?php declare(strict_types=1);

namespace App\Domains\InvoiceRecurring;

use App\Models\InvoiceRecurring as Model;
use App\Domains\FractalAbstract;

class Fractal extends FractalAbstract
{
    /**
     * @param \App\Models\InvoiceRecurring $row
     *
     * @return array
     */
    public static function simple(Model $row): array
    {
        return [
            'id' => $row->id,
            'name' => $row->name,
            'every' => $row->every,
            'enabled' => $row->enabled,
        ];
    }

    /**
     * @param \App\Models\InvoiceRecurring $row
     *
     * @return array
     */
    public static function detail(Model $row): array
    {
        return static::simple($row);
    }

    /**
     * @param \App\Models\InvoiceRecurring $row
     *
     * @return array
     */
    public static function export(Model $row): array
    {
        return static::simple($row);
    }
}
