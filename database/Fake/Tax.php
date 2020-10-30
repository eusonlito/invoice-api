<?php declare(strict_types=1);

namespace Database\Fake;

use App\Models\Tax as Model;

class Tax extends FakeAbstract
{
    /**
     * @return void
     */
    public function run()
    {
        Model::factory()->create([
            'name' => 'IVA 21%',
            'value' => 21,
            'default' => true,
            'company_id' => 1,
            'user_id' => 1
        ]);

        Model::factory()->create([
            'name' => 'IVA 10%',
            'value' => 10,
            'default' => false,
            'company_id' => 1,
            'user_id' => 1
        ]);

        Model::factory()->create([
            'name' => 'IVA 4%',
            'value' => 4,
            'default' => false,
            'company_id' => 1,
            'user_id' => 1
        ]);
    }
}
