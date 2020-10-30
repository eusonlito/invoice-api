<?php declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Support\Facades\Hash;
use App\Models\User as Model;

class UserFactory extends FactoryAbstract
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
            'name' => $this->faker->name,
            'user' => ($user = $this->faker->unique()->companyEmail),
            'password' => Hash::make($user),
            'enabled' => 1,
            'confirmed_at' => date('Y-m-d H:i:s'),
            'company_id' => null,
            'language_id' => 1,
        ];
    }
};
