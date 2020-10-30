<?php declare(strict_types=1);

namespace Database\Factories;

use App\Models\ClientAddress as Model;

class ClientAddressFactory extends FactoryAbstract
{
    /**
     * @var string
     */
    protected $model = Model::class;

    /**
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'postal_code' => $this->faker->postcode,
            'country' => 'España',
            'tax_number' => $this->faker->vat,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,

            'billing' => true,
            'shipping' => true,
            'enabled' => true,

            'client_id' => null,
            'company_id' => null,
            'user_id' => null,
        ];
    }
};
