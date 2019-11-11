<?php declare(strict_types=1);

namespace Database\Fake;

use Illuminate\Support\Facades\Hash;
use App\Models\User as Model;

class User extends FakeAbstract
{
    /**
     * @return void
     */
    public function run()
    {
        Model::insert([
            'name' => 'Demo',
            'user' => 'demo@demo.com',
            'password' => Hash::make('demo@demo.com'),
            'enabled' => 1,
            'confirmed_at' => date('Y-m-d H:i:s'),
            'company_id' => 1,
            'language_id' => 1,
        ]);
    }
}
