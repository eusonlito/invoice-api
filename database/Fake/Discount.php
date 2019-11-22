<?php declare(strict_types=1);

namespace Database\Fake;

use App\Models\Discount as Model;

class Discount extends FakeAbstract
{
    /**
     * @return void
     */
    public function run()
    {
        factory(Model::class)->create([
            'name' => 'IRPF 15%',
            'type' => 'percent',
            'value' => 15,

            'company_id' => 1,
            'user_id' => 1
        ]);

        factory(Model::class)->create([
            'name' => 'IRPF 19%',
            'type' => 'percent',
            'value' => 19,

            'company_id' => 1,
            'user_id' => 1
        ]);

        factory(Model::class)->create([
            'name' => 'IRPF 21%',
            'type' => 'percent',
            'value' => 21,

            'company_id' => 1,
            'user_id' => 1
        ]);
    }
}
