<?php declare(strict_types=1);

namespace Database\Fake;

use App\Models\InvoiceStatus as Model;

class InvoiceStatus extends FakeAbstract
{
    /**
     * @return void
     */
    public function run()
    {
        Model::factory()->create([
            'name' => 'Creada',
            'order' => 1,
            'paid' => false,
            'default' => true,
            'company_id' => 1,
            'user_id' => 1,
        ]);

        Model::factory()->create([
            'name' => 'Enviada',
            'order' => 2,
            'paid' => false,
            'default' => false,
            'company_id' => 1,
            'user_id' => 1,
        ]);

        Model::factory()->create([
            'name' => 'Pagada',
            'order' => 3,
            'paid' => true,
            'default' => false,
            'company_id' => 1,
            'user_id' => 1,
        ]);

        Model::factory()->create([
            'name' => 'Rechazada',
            'order' => 4,
            'paid' => false,
            'default' => false,
            'company_id' => 1,
            'user_id' => 1,
        ]);
    }
}
