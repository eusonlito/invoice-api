<?php declare(strict_types=1);

namespace App\Domains\Product;

use App\Models\Product as Model;
use App\Domains\FractalAbstract;

class Fractal extends FractalAbstract
{
    /**
     * @param \App\Models\Product $row
     *
     * @return array
     */
    public static function simple(Model $row): array
    {
        return [
            'id' => $row->id,
            'reference' => $row->reference,
            'name' => $row->name,
            'price' => $row->price,
            'enabled' => $row->enabled,
        ];
    }

    /**
     * @param \App\Models\Product $row
     *
     * @return array
     */
    public static function detail(Model $row): array
    {
        return static::simple($row);
    }

    /**
     * @param \App\Models\Product $row
     *
     * @return array
     */
    public static function export(Model $row): array
    {
        return static::simple($row);
    }
}
