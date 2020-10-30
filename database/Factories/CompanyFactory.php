<?php declare(strict_types=1);

namespace Database\Factories;

use App\Models\Company as Model;

class CompanyFactory extends FactoryAbstract
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
            'tax_number' => $this->faker->vat,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,

            'country_id' => 68,
            'user_id' => null
        ];
    }
};
