<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Shipping as Model;

$factory->define(Model::class, static function (): array {
    return [
        'name' => null,
        'subtotal' => null,
        'tax_percent' => null,
        'tax_amount' => null,
        'value' => null,
        'default' => null,
        'enabled' => true,
        'company_id' => null,
        'user_id' => null,
    ];
});
