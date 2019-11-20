<?php declare(strict_types=1);

namespace App\Domain\Client;

use App\Models\Client as Model;
use App\Domain\FractalAbstract;
use App\Domain\ClientAddress\Fractal as ClientAddress;
use App\Domain\Discount\Fractal as Discount;
use App\Domain\Payment\Fractal as Payment;
use App\Domain\Shipping\Fractal as Shipping;
use App\Domain\Tax\Fractal as Tax;

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
            'addresses' => ClientAddress::transform('detail', $row->addresses),
        ] + static::simple($row);
    }
}
