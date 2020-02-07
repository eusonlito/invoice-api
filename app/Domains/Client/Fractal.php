<?php declare(strict_types=1);

namespace App\Domains\Client;

use App\Domains\ClientAddress\Fractal as ClientAddress;
use App\Domains\Discount\Fractal as Discount;
use App\Domains\Payment\Fractal as Payment;
use App\Domains\Shipping\Fractal as Shipping;
use App\Domains\Tax\Fractal as Tax;
use App\Models\Client as Model;
use App\Services\Response\FractalAbstract;

class Fractal extends FractalAbstract
{
    /**
     * @param \App\Models\Client $row
     *
     * @return array
     */
    public static function simple(Model $row): array
    {
        return [
            'id' => $row->id,
            'name' => $row->name,
            'phone' => $row->phone,
            'email' => $row->email,
            'contact_name' => $row->contact_name,
            'contact_surname' => $row->contact_surname,
            'created_at' => $row->created_at,
        ];
    }

    /**
     * @param \App\Models\Client $row
     *
     * @return array
     */
    public static function detail(Model $row): array
    {
        return [
            'web' => $row->web,
            'tax_number' => $row->tax_number,
            'type' => $row->type,
            'contact_phone' => $row->contact_phone,
            'contact_email' => $row->contact_email,
            'comment' => $row->comment,
            'discount' => Discount::transform('simple', $row->discount),
            'payment' => Payment::transform('simple', $row->payment),
            'shipping' => Shipping::transform('simple', $row->shipping),
            'tax' => Tax::transform('simple', $row->tax),
        ] + static::simple($row);
    }

    /**
     * @param \App\Models\Client $row
     *
     * @return array
     */
    public static function export(Model $row): array
    {
        return [
            'addresses' => ClientAddress::transform('export', $row->addresses),
        ] + static::detail($row);
    }
}
