<?php declare(strict_types=1);

namespace Database\Factories;

use App\Models\Shipping as Model;

class ShippingFactory extends FactoryAbstract
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
            'subtotal' => null,
            'tax_percent' => null,
            'tax_amount' => null,
            'value' => null,
            'default' => null,
            'enabled' => true,
            'company_id' => null,
            'user_id' => null,
        ];
    }
};
