<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Facades\Hash;
use Faker\Generator;
use App\Models\User as Model;

$factory->define(Model::class, static function (Generator $faker): array {
    return [
        'name' => $faker->name,
        'user' => ($user = $faker->unique()->companyEmail),
        'password' => Hash::make($user),
        'enabled' => 1,
        'confirmed_at' => date('Y-m-d H:i:s'),
        'company_id' => null,
        'language_id' => 1,
    ];
});
