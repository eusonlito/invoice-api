<?php declare(strict_types=1);

namespace App\Domain\InvoiceItem;

use App\Models\InvoiceItem as Model;
use App\Domain\FractalAbstract;
use App\Domain\Product\Fractal as Product;

class Fractal extends FractalAbstract
{
    /**
     * @param \App\Models\InvoiceItem $row
     *
     * @return array
     */
    public static function simple(Model $row): array
    {
        return [
            'id' => $row->id,
            'line' => $row->line,
            'reference' => $row->reference,
            'description' => $row->description,
            'quantity' => $row->quantity,
            'percent_discount' => $row->percent_discount,
            'percent_tax' => $row->percent_tax,
            'amount_price' => $row->amount_price,
            'amount_discount' => $row->amount_discount,
            'amount_tax' => $row->amount_tax,
            'amount_subtotal' => $row->amount_subtotal,
            'amount_total' => $row->amount_total,
        ];
    }

    /**
     * @param \App\Models\InvoiceItem $row
     *
     * @return array
     */
    public static function detail(Model $row): array
    {
        return static::simple($row);
    }

    /**
     * @param \App\Models\InvoiceItem $row
     *
     * @return array
     */
    public static function export(Model $row): array
    {
        return [
            'product' => Product::transform('simple', $row->product),
        ] + static::simple($row);
    }
}