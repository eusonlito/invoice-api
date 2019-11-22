<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Tax as Model;

$factory->define(Model::class, static function (): array {
    return [
        'name' => null,
        'value' => null,
        'default' => null,
        'enabled' => true,
        'company_id' => null,
        'user_id' => null,
    ];
});
