<?php declare(strict_types=1);

namespace Database\Factories;

use App\Models\InvoiceStatus as Model;

class InvoiceStatusFactory extends FactoryAbstract
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
            'order' => null,
            'paid' => null,
            'default' => null,
            'enabled' => true,
            'company_id' => null,
            'user_id' => null,
        ];
    }
};
