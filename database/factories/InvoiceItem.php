<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\InvoiceItem as Model;

$factory->define(Model::class, static function (): array {
    return [
        'line' => null,

        'reference' => null,
        'description' => null,

        'quantity' => null,
        'percent_discount' => null,

        'percent_tax' => null,

        'amount_price' => null,
        'amount_discount' => null,
        'amount_tax' => null,
        'amount_subtotal' => null,
        'amount_total' => null,

        'company_id' => null,
        'invoice_id' => null,
        'product_id' => null,
        'user_id' => null,
    ];
});
