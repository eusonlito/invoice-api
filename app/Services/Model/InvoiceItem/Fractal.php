<?php declare(strict_types=1);

namespace App\Services\Model\InvoiceItem;

use App\Models\InvoiceItem as Model;
use App\Services\Model\FractalAbstract;
use App\Services\Model\Product\Fractal as Product;

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
            'line' => (int)$row->line,
            'reference' => $row->reference,
            'description' => $row->description,
            'quantity' => (float)$row->quantity,
            'percent_discount' => (float)$row->percent_discount,
            'percent_tax' => (float)$row->percent_tax,
            'amount_price' => (float)$row->amount_price,
            'amount_discount' => (float)$row->amount_discount,
            'amount_tax' => (float)$row->amount_tax,
            'amount_subtotal' => (float)$row->amount_subtotal,
            'amount_total' => (float)$row->amount_total,
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
