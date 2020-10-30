<?php declare(strict_types=1);

namespace Database\Factories;

use App\Models\Invoice as Model;

class InvoiceFactory extends FactoryAbstract
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
            'number' => null,

            'company_name' => null,
            'company_address' => null,
            'company_city' => null,
            'company_state' => null,
            'company_postal_code' => null,
            'company_country' => null,
            'company_tax_number' => null,
            'company_phone' => null,
            'company_email' => null,

            'billing_name' => null,
            'billing_address' => null,
            'billing_city' => null,
            'billing_state' => null,
            'billing_postal_code' => null,
            'billing_country' => null,
            'billing_tax_number' => null,

            'shipping_name' => null,
            'shipping_address' => null,
            'shipping_city' => null,
            'shipping_state' => null,
            'shipping_postal_code' => null,
            'shipping_country' => null,

            'date_at' => null,

            'company_id' => null,
            'client_id' => null,
            'client_address_billing_id' => null,
            'client_address_shipping_id' => null,
            'invoice_serie_id' => null,
            'user_id' => null,
        ];
    }
}
