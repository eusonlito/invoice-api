<?php declare(strict_types=1);

namespace App\Services\Model\Product;

use App\Models\Product as Model;
use App\Services\Model\FractalAbstract;

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
            'price' => (float)$row->price,
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
}
