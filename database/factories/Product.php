<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product as Model;

$factory->define(Model::class, static function (): array {
    return [
        'reference' => null,
        'name' => null,
        'price' => null,
        'enabled' => true,
        'company_id' => null,
        'user_id' => null,
    ];
});
