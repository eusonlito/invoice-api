<?php declare(strict_types=1);

namespace Database\Factories;

use App\Models\InvoiceRecurring as Model;

class InvoiceRecurringFactory extends FactoryAbstract
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
            'every' => 'week',
            'enabled' => true,
            'company_id' => null,
            'user_id' => null,
        ];
    }
};
