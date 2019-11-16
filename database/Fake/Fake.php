<?php declare(strict_types=1);

namespace Database\Fake;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Fake extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $time = time();

        DB::transaction(function () {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $this->call(User::class);
            $this->call(Company::class);
            $this->call(Discount::class);
            $this->call(Payment::class);
            $this->call(Product::class);
            $this->call(Shipping::class);
            $this->call(Tax::class);
            $this->call(Client::class);
            $this->call(InvoiceSerie::class);
            $this->call(InvoiceStatus::class);
            $this->call(Invoice::class);

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        });

        $this->command->info(sprintf('Seeding: Total Time %s seconds', time() - $time));
    }
}
