<?php declare(strict_types=1);

namespace Database\Fake;

use App\Models\InvoiceConfiguration as Model;

class InvoiceConfiguration extends FakeAbstract
{
    /**
     * @return void
     */
    public function run()
    {
        Model::insert([
            [
                'key' => 'number_prefix',
                'value' => date('Y-'),
                'public' => 1,
                'company_id' => 1,
                'user_id' => 1
            ],

            [
                'key' => 'number_fill',
                'value' => 4,
                'public' => 1,
                'company_id' => 1,
                'user_id' => 1
            ],

            [
                'key' => 'number_next',
                'value' => 1,
                'public' => 1,
                'company_id' => 1,
                'user_id' => 1
            ]
        ]);
    }
}
