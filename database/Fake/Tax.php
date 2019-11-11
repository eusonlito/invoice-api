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
        Model::insert([
            [
                'name' => 'IVA 21%',
                'value' => 21,
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1
            ],

            [
                'name' => 'IVA 10%',
                'value' => 10,
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1
            ],

            [
                'name' => 'IVA 4%',
                'value' => 4,
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1
            ]
        ]);
    }
}
