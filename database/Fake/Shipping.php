<?php declare(strict_types=1);

namespace Database\Fake;

use App\Models\Shipping as Model;

class Shipping extends FakeAbstract
{
    /**
     * @return void
     */
    public function run()
    {
        Model::insert([
            [
                'name' => 'Correos Express',
                'value' => 5,
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1
            ],

            [
                'name' => 'SEUR 24',
                'value' => 10,
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1
            ]
        ]);
    }
}
