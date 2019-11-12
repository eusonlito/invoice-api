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
        Model::insert([
            [
                'name' => 'IRPF 15%',
                'type' => 'percent',
                'value' => 15,
                'default' => true,
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1
            ],

            [
                'name' => 'IRPF 19%',
                'type' => 'percent',
                'value' => 19,
                'default' => false,
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1
            ],

            [
                'name' => 'IRPF 21%',
                'type' => 'percent',
                'value' => 21,
                'default' => false,
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1
            ]
        ]);
    }
}
