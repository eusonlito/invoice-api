<?php declare(strict_types=1);

namespace Database\Factories;

use App\Models\Discount as Model;

class DiscountFactory extends FactoryAbstract
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
            'type' => null,
            'value' => null,
            'default' => true,
            'enabled' => true,
            'company_id' => null,
            'user_id' => null
        ];
    }
};
