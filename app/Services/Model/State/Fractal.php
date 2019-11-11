<?php declare(strict_types=1);

namespace App\Services\Model\State;

use App\Models\State as Model;
use App\Services\Model\FractalAbstract;
use App\Services\Model\Country\Fractal as Country;

class Fractal extends FractalAbstract
{
    /**
     * @param \App\Models\State $row
     *
     * @return array
     */
    public static function simple(Model $row): array
    {
        return [
            'id' => $row->id,
            'name' => $row->name,
            'country' => static::relationIfLoaded($row, 'country', Country::class),
        ];
    }

    /**
     * @param \App\Models\State $row
     *
     * @return array
     */
    public static function detail(Model $row): array
    {
        return [
            'country' => Country::transform('simple', $row->country)
        ] + static::simple($row);
    }
}
