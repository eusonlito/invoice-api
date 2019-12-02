<?php declare(strict_types=1);

namespace Database\Fake;

use App\Models\InvoiceRecurring as Model;

class InvoiceRecurring extends FakeAbstract
{
    /**
     * @return void
     */
    public function run()
    {
        factory(Model::class)->create([
            'name' => 'Semanal',
            'every' => 'week',
            'enabled' => true,
            'company_id' => 1,
            'user_id' => 1,
        ]);

        factory(Model::class)->create([
            'name' => 'Mensual',
            'every' => 'month',
            'enabled' => true,
            'company_id' => 1,
            'user_id' => 1,
        ]);

        factory(Model::class)->create([
            'name' => 'Anual',
            'every' => 'year',
            'enabled' => true,
            'company_id' => 1,
            'user_id' => 1,
        ]);
    }
}
