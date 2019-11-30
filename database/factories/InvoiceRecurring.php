<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\InvoiceRecurring as Model;

$factory->define(Model::class, static function (): array {
    return [
        'name' => null,
        'every' => 'week',
        'enabled' => true,
        'company_id' => null,
        'user_id' => null,
    ];
});
