<?php declare(strict_types=1);

namespace Database\Fake;

use App\Models\Payment as Model;

class Payment extends FakeAbstract
{
    /**
     * @return void
     */
    public function run()
    {
        Model::insert([
            [
                'name' => 'Transferencia Bancaria',
                'description' => 'Realizar ingreso en la siguiente cuenta bancaria indicando el número de factura.',
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1
            ],

            [
                'name' => 'Paypal',
                'description' => 'Realizar el ingreso en la siguiente cuenta indicando el número de factura.',
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1
            ]
        ]);
    }
}
