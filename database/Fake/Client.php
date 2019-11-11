<?php declare(strict_types=1);

namespace Database\Fake;

use Illuminate\Support\Facades\Hash;
use App\Models;
use App\Models\Client as Model;

class Client extends FakeAbstract
{
    /**
     * @return void
     */
    public function run()
    {
        for ($i = rand(50, 150); $i > 0; --$i) {
            $this->client();
        }
    }

    /**
     * @return void
     */
    protected function client()
    {
        $faker = $this->faker();

        $row = Model::create([
            'name' => $faker->company,
            'phone' => $faker->phoneNumber,
            'email' => $faker->email,
            'web' => $faker->url,
            'tax_number' => $faker->vat,

            'contact_name' => $faker->firstName,
            'contact_surname' => $faker->lastName,
            'contact_phone' => $faker->phoneNumber,
            'contact_email' => $faker->email,

            'company_id' => 1,
            'discount_id' => rand(1, 3),
            'payment_id' => rand(1, 2),
            'shipping_id' => rand(1, 2),
            'tax_id' => rand(1, 3),
            'user_id' => 1,
        ]);

        for ($i = rand(2, 4); $i > 0; --$i) {
            $this->address($row);
        }
    }

    /**
     * @param \App\Models\Client $row
     *
     * @return void
     */
    protected function address(Model $row)
    {
        $faker = $this->faker();

        Models\ClientAddress::insert([
            'name' => $faker->company,
            'address' => $faker->streetAddress,
            'city' => $faker->city,
            'state' => $faker->state,
            'postal_code' => $faker->postcode,
            'country' => 'España',
            'tax_number' => $faker->vat,
            'email' => $faker->email,
            'phone' => $faker->phoneNumber,

            'billing' => (bool)rand(0, 4),
            'shipping' => (bool)rand(0, 4),
            'enabled' => true,

            'client_id' => $row->id,
            'company_id' => 1,
            'user_id' => 1,
        ]);
    }
}
