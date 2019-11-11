<?php declare(strict_types=1);

namespace Database\Fake;

use Illuminate\Support\Facades\Hash;
use App\Models;
use App\Models\Company as Model;

class Company extends FakeAbstract
{
    /**
     * @return void
     */
    public function run()
    {
        $faker = $this->faker();

        Model::insert([
            'name' => $faker->company,
            'address' => $faker->streetAddress,
            'city' => $faker->city,
            'postal_code' => $faker->postcode,
            'tax_number' => $faker->vat,
            'email' => $faker->email,
            'phone' => $faker->phoneNumber,

            'state_id' => Models\Country::where('default', true)->first()->states()->inRandomOrder()->first()->id,
            'user_id' => 1
        ]);
    }
}
