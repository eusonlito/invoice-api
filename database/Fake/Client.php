<?php declare(strict_types=1);

namespace Database\Fake;

use App\Models;
use App\Models\Client as Model;

class Client extends FakeAbstract
{
    /**
     * @return void
     */
    public function run()
    {
        for ($i = rand(50, 150); $i > 0; --$i) {
            $this->client();
        }
    }

    /**
     * @return void
     */
    protected function client()
    {
        $row = factory(Model::class)->create([
            'company_id' => 1,
            'discount_id' => rand(1, 3),
            'payment_id' => rand(1, 2),
            'shipping_id' => rand(1, 2),
            'tax_id' => rand(1, 3),
            'user_id' => 1
        ]);

        for ($i = rand(2, 4); $i > 0; --$i) {
            $this->address($row);
        }
    }

    /**
     * @param \App\Models\Client $row
     *
     * @return void
     */
    protected function address(Model $row)
    {
        factory(Models\ClientAddress::class)->create([
            'billing' => (bool)rand(0, 4),
            'shipping' => (bool)rand(0, 4),

            'client_id' => $row->id,
            'company_id' => 1,
            'user_id' => 1
        ]);
    }
}
