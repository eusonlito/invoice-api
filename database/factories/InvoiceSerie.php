<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\InvoiceSerie as Model;

$factory->define(Model::class, static function (): array {
    return [
        'name' => null,
        'number_prefix' => null,
        'number_fill' => 3,
        'number_next' => 1,
        'default' => null,
        'enabled' => true,
        'company_id' => null,
        'user_id' => null,
    ];
});
