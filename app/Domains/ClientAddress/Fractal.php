<?php declare(strict_types=1);

namespace App\Domains\ClientAddress;

use App\Models\ClientAddress as Model;
use App\Domains\FractalAbstract;

class Fractal extends FractalAbstract
{
    /**
     * @param \App\Models\ClientAddress $row
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
            'country' => $row->country,
            'phone' => $row->phone,
            'email' => $row->email,
            'comment' => $row->comment,
            'tax_number' => $row->tax_number,
            'billing' => $row->billing,
            'shipping' => $row->shipping,
            'enabled' => $row->enabled,
            'client' => ['id' => $row->client_id],
        ];
    }

    /**
     * @param \App\Models\ClientAddress $row
     *
     * @return array
     */
    public static function detail(Model $row): array
    {
        return [
            'comment' => $row->comment,
        ] + static::simple($row);
    }

    /**
     * @param \App\Models\ClientAddress $row
     *
     * @return array
     */
    public static function export(Model $row): array
    {
        return static::detail($row);
    }
}
