<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator;
use App\Models\ClientAddress as Model;

$factory->define(Model::class, static function (Generator $faker): array {
    return [
        'name' => $faker->company,
        'address' => $faker->streetAddress,
        'city' => $faker->city,
        'state' => $faker->state,
        'postal_code' => $faker->postcode,
        'country' => 'España',
        'tax_number' => $faker->vat,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,

        'billing' => true,
        'shipping' => true,
        'enabled' => true,

        'client_id' => null,
        'company_id' => null,
        'user_id' => null,
    ];
});
