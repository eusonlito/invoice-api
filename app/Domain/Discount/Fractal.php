<?php declare(strict_types=1);

namespace App\Domain\Discount;

use App\Models\Discount as Model;
use App\Domain\FractalAbstract;

class Fractal extends FractalAbstract
{
    /**
     * @param \App\Models\Discount $row
     *
     * @return array
     */
    public static function simple(Model $row): array
    {
        return [
            'id' => $row->id,
            'name' => $row->name,
            'type' => $row->type,
            'value' => $row->value,
            'default' => $row->default,
            'enabled' => $row->enabled,
        ];
    }

    /**
     * @param \App\Models\Discount $row
     *
     * @return array
     */
    public static function detail(Model $row): array
    {
        return [
            'description' => $row->description
        ] + static::simple($row);
    }
}