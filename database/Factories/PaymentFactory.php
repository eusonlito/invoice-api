<?php declare(strict_types=1);

namespace Database\Factories;

use App\Models\Payment as Model;

class PaymentFactory extends FactoryAbstract
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
            'name' => null,
            'description' => null,
            'default' => null,
            'enabled' => true,
            'company_id' => null,
            'user_id' => null,
        ];
    }
};
