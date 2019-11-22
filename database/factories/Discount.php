<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Discount as Model;

$factory->define(Model::class, static function (): array {
    return [
        'name' => null,
        'type' => null,
        'value' => null,
        'default' => true,
        'enabled' => true,
        'company_id' => null,
        'user_id' => null
    ];
});
