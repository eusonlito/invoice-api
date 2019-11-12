<?php declare(strict_types=1);

namespace App\Services\Model\Shipping;

use App\Models\Shipping as Model;
use App\Services\Model\FractalAbstract;

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
            'value' => $row->value,
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
}
