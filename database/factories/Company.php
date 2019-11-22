<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator;
use App\Models\Company as Model;

$factory->define(Model::class, static function (Generator $faker): array {
    return [
        'name' => $faker->company,
        'address' => $faker->streetAddress,
        'city' => $faker->city,
        'state' => $faker->state,
        'postal_code' => $faker->postcode,
        'tax_number' => $faker->vat,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,

        'country_id' => 68,
        'user_id' => null
    ];
});
