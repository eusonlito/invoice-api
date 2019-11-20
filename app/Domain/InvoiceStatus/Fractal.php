<?php declare(strict_types=1);

namespace App\Domain\InvoiceStatus;

use App\Models\InvoiceStatus as Model;
use App\Domain\FractalAbstract;

class Fractal extends FractalAbstract
{
    /**
     * @param \App\Models\InvoiceStatus $row
     *
     * @return array
     */
    public static function simple(Model $row): array
    {
        return [
            'id' => $row->id,
            'name' => $row->name,
            'order' => $row->order,
            'paid' => $row->paid,
            'default' => $row->default,
            'enabled' => $row->enabled,
        ];
    }

    /**
     * @param \App\Models\InvoiceStatus $row
     *
     * @return array
     */
    public static function detail(Model $row): array
    {
        return static::simple($row);
    }
}
