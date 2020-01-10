<?php declare(strict_types=1);

namespace App\Console\Commands\Invoice;

use App\Domains\Notification\Store as NotificationStore;
use App\Models\Invoice as Model;

class Unpaid extends InvoiceAbstract
{
    /**
     * @var string
     */
    protected $signature = 'invoice:unpaid';

    /**
     * @var string
     */
    protected $description = 'Generate unpaid invoices notifications';

    /**
     * @return void
     */
    public function handle()
    {
        Model::unpaidAt(date('Y-m-d', strtotime('-1 day')))
            ->with(['user'])
            ->get()
            ->each(fn ($row) => $this->notify($row));
    }

    /**
     * @param \App\Models\Invoice $row
     *
     * @return void
     */
    protected function notify(Model $row)
    {
        $this->info(sprintf('Notify Invoice Unpaid [%s] %s', $row->id, $row->number));

        (new NotificationStore($row->user, null, [
            'code' => 'invoice.unpaid',
            'title' => __('notification.invoice.unpaid.title', ['number' => $row->number]),
            'status' => 'danger',
            'invoice_id' => $row->id
        ]))->create();
    }
}
