<?php declare(strict_types=1);

namespace Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Database extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $time = time();

        $this->call(Configuration::class);
        $this->call(Country::class);
        $this->call(Language::class);

        $this->command->info(sprintf('Seeding: Total Time %s seconds', time() - $time));
    }
}
