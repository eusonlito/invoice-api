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
        Model::factory()->create([
            'name' => 'Correos Express',
            'subtotal' => 5,
            'tax_percent' => 21,
            'tax_amount' => (5 * 0.21),
            'value' => (5 * 1.21),
            'default' => true,
            'company_id' => 1,
            'user_id' => 1
        ]);

        Model::factory()->create([
            'name' => 'SEUR 24',
            'subtotal' => 10,
            'tax_percent' => 21,
            'tax_amount' => (10 * 0.21),
            'value' => (10 * 1.21),
            'default' => false,
            'company_id' => 1,
            'user_id' => 1
        ]);
    }
}
