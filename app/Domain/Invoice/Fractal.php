<?php declare(strict_types=1);

namespace App\Domain\Invoice;

use App\Models\Invoice as Model;
use App\Domain\FractalAbstract;
use App\Domain\Client\Fractal as Client;
use App\Domain\ClientAddress\Fractal as ClientAddress;
use App\Domain\Discount\Fractal as Discount;
use App\Domain\InvoiceFile\Fractal as InvoiceFile;
use App\Domain\InvoiceItem\Fractal as InvoiceItem;
use App\Domain\InvoiceSerie\Fractal as InvoiceSerie;
use App\Domain\InvoiceStatus\Fractal as InvoiceStatus;
use App\Domain\Payment\Fractal as Payment;
use App\Domain\Shipping\Fractal as Shipping;
use App\Domain\Tax\Fractal as Tax;

class Fractal extends FractalAbstract
{
    /**
     * @param \App\Models\Invoice $row
     *
     * @return array
     */
    public static function simple(Model $row): array
    {
        return [
            'id' => $row->id,
            'number' => $row->number,
            'billing_name' => $row->billing_name,
            'amount_total' => $row->amount_total,
            'amount_paid' => $row->amount_paid,
            'amount_due' => $row->amount_due,
            'date_at' => $row->date_at,
            'paid_at' => $row->paid_at,
            'created_at' => $row->created_at,
            'serie' => InvoiceSerie::transform('simple', $row->serie),
            'status' => InvoiceStatus::transform('simple', $row->status)
        ];
    }

    /**
     * @param \App\Models\Invoice $row
     *
     * @return array
     */
    public static function detail(Model $row): array
    {
        return [
            'company_name' => $row->company_name,
            'company_address' => $row->company_address,
            'company_city' => $row->company_city,
            'company_state' => $row->company_state,
            'company_postal_code' => $row->company_postal_code,
            'company_country' => $row->company_country,
            'company_tax_number' => $row->company_tax_number,

            'billing_address' => $row->billing_address,
            'billing_city' => $row->billing_city,
            'billing_state' => $row->billing_state,
            'billing_postal_code' => $row->billing_postal_code,
            'billing_country' => $row->billing_country,
            'billing_tax_number' => $row->billing_tax_number,

            'shipping_name' => $row->shipping_name,
            'shipping_address' => $row->shipping_address,
            'shipping_city' => $row->shipping_city,
            'shipping_state' => $row->shipping_state,
            'shipping_postal_code' => $row->shipping_postal_code,
            'shipping_country' => $row->shipping_country,

            'quantity' => $row->quantity,

            'percent_discount' => $row->percent_discount,
            'percent_tax' => $row->percent_tax,

            'amount_subtotal' => $row->amount_subtotal,
            'amount_discount' => $row->amount_discount,
            'amount_tax' => $row->amount_tax,
            'amount_shipping' => $row->amount_shipping,

            'required_at' => $row->required_at,

            'comment_private' => $row->comment_private,
            'comment_public' => $row->comment_public,

            'items' => InvoiceItem::transform('simple', $row->items),

            'clientAddressBilling' => ClientAddress::transform('simple', $row->clientAddressBilling),
            'clientAddressShipping' => ClientAddress::transform('simple', $row->clientAddressShipping),
            'discount' => Discount::transform('simple', $row->discount),
            'files' => InvoiceFile::transform('simple', $row->files),
            'payment' => Payment::transform('simple', $row->payment),
            'shipping' => Shipping::transform('simple', $row->shipping),
            'tax' => Tax::transform('simple', $row->tax),
        ] + static::simple($row);
    }

    /**
     * @param \App\Models\Invoice $row
     *
     * @return array
     */
    public static function export(Model $row): array
    {
        return [
            'client' => Client::transform('simple', $row->client),
            'items' => InvoiceItem::transform('export', $row->items),
        ] + static::detail($row);
    }
}
