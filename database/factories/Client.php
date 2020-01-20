<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator;
use App\Models\Client as Model;

$factory->define(Model::class, static function (Generator $faker): array {
    return [
        'name' => $faker->company,
        'phone' => $faker->phoneNumber,
        'email' => $faker->email,
        'web' => $faker->url,
        'tax_number' => $faker->vat,
        'type' => 'company',

        'contact_name' => $faker->firstName,
        'contact_surname' => $faker->lastName,
        'contact_phone' => $faker->phoneNumber,
        'contact_email' => $faker->email,

        'company_id' => null,
        'discount_id' => null,
        'payment_id' => null,
        'shipping_id' => null,
        'tax_id' => null,
        'user_id' => null,
    ];
});
