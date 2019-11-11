<?php declare(strict_types=1);

namespace Database\Fake;

use App\Models\Product as Model;

class Product extends FakeAbstract
{
    /**
     * @return void
     */
    public function run()
    {
        Model::insert([
            [
                'reference' => 'HOSTING-01',
                'name' => 'Alojamiento Web Básico (1 año)',
                'price' => 60,
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1
            ],

            [
                'reference' => 'HOSTING-02',
                'name' => 'Alojamiento Web WordPress (1 año)',
                'price' => 80,
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1
            ],

            [
                'reference' => 'HOSTING-03',
                'name' => 'Alojamiento Web PrestaShop (1 año)',
                'price' => 90,
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1
            ],

            [
                'reference' => 'DOMAIN-01',
                'name' => 'Dominio .com (1 año)',
                'price' => 90,
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1
            ],

            [
                'reference' => 'DOMAIN-02',
                'name' => 'Dominio .io (1 año)',
                'price' => 40,
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1
            ],

            [
                'reference' => 'WEB-01',
                'name' => 'Plantilla WordPress',
                'price' => 200,
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1
            ],

            [
                'reference' => 'WEB-02',
                'name' => 'Plantilla PrestaShop',
                'price' => 240,
                'enabled' => true,
                'company_id' => 1,
                'user_id' => 1
            ]
        ]);
    }
}
