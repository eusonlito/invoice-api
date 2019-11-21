<?php declare(strict_types=1);

namespace App\Domains\Country;

use App\Models\Country as Model;
use App\Domains\FractalAbstract;

class Fractal extends FractalAbstract
{
    /**
     * @param \App\Models\Country $row
     *
     * @return array
     */
    public static function simple(Model $row): array
    {
        return [
            'id' => $row->id,
            'iso' => $row->iso,
            'name' => $row->name
        ];
    }

    /**
     * @param \App\Models\Country $row
     *
     * @return array
     */
    public static function detail(Model $row): array
    {
        return static::simple($row);
    }
}
