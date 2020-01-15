<?php declare(strict_types=1);

namespace App\Domains\Shipping;

use App\Models\Shipping as Model;
use App\Domains\FractalAbstract;

class Fractal extends FractalAbstract
{
    /**
     * @param \App\Models\Shipping $row
     *
     * @return array
     */
    public static function simple(Model $row): array
    {
        return [
            'id' => $row->id,
            'name' => $row->name,
            'subtotal' => $row->subtotal,
            'tax_percent' => $row->tax_percent,
            'tax_amount' => $row->tax_amount,
            'value' => $row->value,
            'tax' => $row->tax,
            'default' => $row->default,
            'enabled' => $row->enabled,
        ];
    }

    /**
     * @param \App\Models\Shipping $row
     *
     * @return array
     */
    public static function detail(Model $row): array
    {
        return [
            'description' => $row->description
        ] + static::simple($row);
    }

    /**
     * @param \App\Models\Shipping $row
     *
     * @return array
     */
    public static function export(Model $row): array
    {
        return static::detail($row);
    }
}
