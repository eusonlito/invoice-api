<?php declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product as Model;

class ProductFactory extends FactoryAbstract
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
            'reference' => null,
            'name' => null,
            'price' => null,
            'enabled' => true,
            'company_id' => null,
            'user_id' => null,
        ];
    }
};
