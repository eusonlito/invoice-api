<?php declare(strict_types=1);

namespace Database\Factories;

use App\Models\InvoiceSerie as Model;

class InvoiceSerieFactory extends FactoryAbstract
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
            'number_prefix' => null,
            'number_fill' => 3,
            'number_next' => 1,
            'default' => null,
            'enabled' => true,
            'company_id' => null,
            'user_id' => null,
        ];
    }
};