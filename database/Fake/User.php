<?php declare(strict_types=1);

namespace Database\Fake;

use App\Models\User as Model;

class User extends FakeAbstract
{
    /**
     * @return void
     */
    public function run()
    {
        factory(Model::class)->create(['company_id' => 1]);
    }
}
