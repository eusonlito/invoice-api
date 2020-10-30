<?php declare(strict_types=1);

namespace Database\Factories;

use App\Models\InvoiteItem as Model;

class InvoiceItemFactory extends FactoryAbstract
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
            'line' => null,

            'reference' => null,
            'description' => null,

            'quantity' => null,
            'percent_discount' => null,

            'percent_tax' => null,

            'amount_price' => null,
            'amount_discount' => null,
            'amount_tax' => null,
            'amount_subtotal' => null,
            'amount_total' => null,

            'company_id' => null,
            'invoice_id' => null,
            'product_id' => null,
            'user_id' => null,
        ];
    }
};
