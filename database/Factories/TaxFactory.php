<?php declare(strict_types=1);

namespace Database\Factories;

use App\Models\Tax as Model;

class TaxFactory extends FactoryAbstract
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
            'value' => null,
            'default' => null,
            'enabled' => true,
            'company_id' => null,
            'user_id' => null,
        ];
    }
};
