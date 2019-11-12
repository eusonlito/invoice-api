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
        Model::insert([
            [
                'name' => 'Creada',
                'order' => 1,
                'paid' => false,
                'default' => true,
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1,
            ],

            [
                'name' => 'Enviada',
                'order' => 2,
                'paid' => false,
                'default' => false,
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1,
            ],

            [
                'name' => 'Pagada',
                'order' => 3,
                'paid' => true,
                'default' => false,
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1,
            ],

            [
                'name' => 'Rechazada',
                'order' => 4,
                'paid' => false,
                'default' => false,
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1,
            ]
        ]);
    }
}
