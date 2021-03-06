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
        Model::factory()->create([
            'name' => 'Factura',
            'number_prefix' => date('Y-'),
            'default' => true,
            'company_id' => 1,
            'user_id' => 1,
        ]);

        Model::factory()->create([
            'name' => 'Presupuesto',
            'number_prefix' => ('PRE-'.date('Y-')),
            'default' => false,
            'company_id' => 1,
            'user_id' => 1,
        ]);

        Model::factory()->create([
            'name' => 'Proforma',
            'number_prefix' => ('PRO-'.date('Y-')),
            'default' => false,
            'company_id' => 1,
            'user_id' => 1,
        ]);

        Model::factory()->create([
            'name' => 'Rectificativa',
            'number_prefix' => ('REC-'.date('Y-')),
            'default' => false,
            'company_id' => 1,
            'user_id' => 1,
        ]);
    }
}
