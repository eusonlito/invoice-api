<?php declare(strict_types=1);

namespace Database\Fake;

use App\Models\Company as Model;

class Company extends FakeAbstract
{
    /**
     * @return void
     */
    public function run()
    {
        factory(Model::class)->create(['user_id' => 1]);
    }
}
