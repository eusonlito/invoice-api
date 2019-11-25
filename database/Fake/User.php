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
        factory(Model::class)->create([
            'name' => 'Demo',
            'user' => 'demo@demo.com',
            'password' => Hash::make('demo@demo.com'),
            'company_id' => 1
        ]);
    }
}
