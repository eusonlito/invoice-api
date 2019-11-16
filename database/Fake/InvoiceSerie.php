<?php declare(strict_types=1);

namespace Database\Fake;

use App\Models\InvoiceSerie as Model;

class InvoiceSerie extends FakeAbstract
{
    /**
     * @return void
     */
    public function run()
    {
        Model::insert([
            [
                'name' => 'Factura',
                'number_prefix' => date('Y-'),
                'number_fill' => 3,
                'number_next' => 1,
                'default' => true,
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1,
            ],
            [
                'name' => 'Presupuesto',
                'number_prefix' => ('PRE-'.date('Y-')),
                'number_fill' => 3,
                'number_next' => 1,
                'default' => false,
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1,
            ],
            [
                'name' => 'Proforma',
                'number_prefix' => ('PRO-'.date('Y-')),
                'number_fill' => 3,
                'number_next' => 1,
                'default' => false,
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1,
            ],
            [
                'name' => 'Rectificativa',
                'number_prefix' => ('REC-'.date('Y-')),
                'number_fill' => 3,
                'number_next' => 1,
                'default' => false,
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1,
            ],
        ]);
    }
}
