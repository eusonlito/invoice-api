<?php declare(strict_types=1);

namespace App\Domains\Tax;

use App\Models\Tax as Model;
use App\Services\Response\FractalAbstract;

class Fractal extends FractalAbstract
{
    /**
     * @param \App\Models\Tax $row
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
     * @param \App\Models\Tax $row
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
     * @param \App\Models\Tax $row
     *
     * @return array
     */
    public static function export(Model $row): array
    {
        return static::detail($row);
    }
}
