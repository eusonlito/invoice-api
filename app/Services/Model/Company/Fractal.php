<?php declare(strict_types=1);

namespace App\Services\Model\Company;

use App\Models\Company as Model;
use App\Services\Model\FractalAbstract;
use App\Services\Model\State\Fractal as State;

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
            'postal_code' => $row->postal_code,
            'tax_number' => $row->tax_number,
            'phone' => $row->phone,
            'email' => $row->email,
            'state' => State::transform('detail', $row->state),
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
