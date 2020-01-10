<?php declare(strict_types=1);

namespace App\Domains\Invoice\Service;

use Illuminate\Support\Collection;
use App\Models\Invoice as Model;
use App\Services\Csv\Write;

class Csv
{
    /**
     * @param \Illuminate\Support\Collection $list
     *
     * @return string
     */
    public static function export(Collection $list): string
    {
        $csv = [];

        foreach ($list as $row) {
            $csv[] = static::row($row);
        }

        return $csv ? Write::fromArray($csv) : '';
    }

    /**
     * @param \App\Models\Invoice $row
     *
     * @return array
     */
    protected static function row(Model $row): array
    {
        return [
            'number' => $row->number,

            'date_at' => $row->date_at,
            'amount_total' => $row->amount_total,

            'serie' => $row->serie->name,
            'status' => $row->status->name,

            'company_name' => $row->company_name,
            'company_address' => $row->company_address,
            'company_city' => $row->company_city,
            'company_state' => $row->company_state,
            'company_postal_code' => $row->company_postal_code,
            'company_country' => $row->company_country,
            'company_tax_number' => $row->company_tax_number,

            'billing_name' => $row->billing_name,
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

            'amount_paid' => $row->amount_paid,
            'amount_due' => $row->amount_due,

            'paid_at' => $row->paid_at,
            'required_at' => $row->required_at,

            'discount' => ($row->discount->name ?? ''),
            'payment' => ($row->payment->name ?? ''),
            'recurring' => ($row->recurring->name ?? ''),
            'shipping' => ($row->shipping->name ?? ''),
            'tax' => ($row->tax->name ?? ''),
        ];
    }
}
