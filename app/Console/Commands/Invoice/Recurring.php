<?php declare(strict_types=1);

namespace App\Console\Commands\Invoice;

use App\Domains\Invoice\Event\Recurring as Event;
use App\Models\Invoice as Model;

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
        Model::select('id', 'number')
            ->pendingToRecurring()
            ->get()
            ->each(fn ($row) => $this->enqueue($row));
    }

    /**
     * @param \App\Models\Invoice $row
     *
     * @return void
     */
    protected function enqueue(Model $row)
    {
        $this->info(sprintf('Procesing Invoice [%s] %s', $row->id, $row->number));

        event(new Event($row->id));
    }
}
