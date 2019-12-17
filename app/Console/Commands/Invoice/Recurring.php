<?php declare(strict_types=1);

namespace App\Console\Commands\Invoice;

use App\Domains\Invoice\Store\Recurring as StoreRecurring;

class Recurring extends InvoiceAbstract
{
    /**
     * @var string
     */
    protected $signature = 'invoice:recurring';

    /**
     * @var string
     */
    protected $description = 'Generate pending recurring invoices';

    /**
     * @return void
     */
    public function handle()
    {
        StoreRecurring::pending();
    }
}
