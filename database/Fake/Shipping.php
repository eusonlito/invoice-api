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
        factory(Model::class)->create([
            'name' => 'Correos Express',
            'value' => 5,
            'default' => true,
            'company_id' => 1,
            'user_id' => 1
        ]);

        factory(Model::class)->create([
            'name' => 'SEUR 24',
            'value' => 10,
            'default' => false,
            'company_id' => 1,
            'user_id' => 1
        ]);
    }
}
