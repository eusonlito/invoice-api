<?php declare(strict_types=1);

namespace Database\Factories;

use App\Models\Client as Model;

class ClientFactory extends FactoryAbstract
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
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'web' => $this->faker->url,
            'tax_number' => $this->faker->vat,
            'type' => 'company',

            'contact_name' => $this->faker->firstName,
            'contact_surname' => $this->faker->lastName,
            'contact_phone' => $this->faker->phoneNumber,
            'contact_email' => $this->faker->email,

            'company_id' => null,
            'discount_id' => null,
            'payment_id' => null,
            'shipping_id' => null,
            'tax_id' => null,
            'user_id' => null,
        ];
    }
};
