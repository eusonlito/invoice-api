<?php declare(strict_types=1);

namespace App\Domains\Company;

use App\Models\Company as Model;
use App\Domains\FractalAbstract;
use App\Domains\Country\Fractal as Country;

class Fractal extends FractalAbstract
{
    /**
     * @param \App\Models\Company $row
     *
     * @return array
     */
    public static function simple(Model $row): array
    {
        return [
            'id' => $row->id,
            'name' => $row->name,
            'address' => $row->address,
            'city' => $row->city,
            'state' => $row->state,
            'postal_code' => $row->postal_code,
            'tax_number' => $row->tax_number,
            'phone' => $row->phone,
            'email' => $row->email,
            'country' => Country::transform('detail', $row->country),
        ];
    }

    /**
     * @param \App\Models\Company $row
     *
     * @return array
     */
    public static function detail(Model $row): array
    {
        return static::simple($row);
    }
}
