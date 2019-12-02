<?php declare(strict_types=1);

namespace App\Domains\Invoice;

use Illuminate\Support\Collection;
use App\Domains;
use App\Domains\StoreAbstract;
use App\Models;
use App\Models\Invoice as Model;

class StoreRecurring extends StoreAbstract
{
    /**
     * @return void
     */
    public static function pending()
    {
        foreach (Model::pendingToRecurring()->cursor() as $row) {
            event(new Event\Recurring($row));
        }
    }

    /**
     * @param \App\Models\Invoice $row
     *
     * @return void
     */
    public function invoice(Model $row)
    {
        $row->setRelations([]);

        $this->row = new Model($row->toArray());

        $this->status();
        $this->values();

        $this->row->save();

        $this->items($row->items);

        $this->file();
        $this->configuration();

        $this->row->save();

        $row->recurring_at = null;
        $row->save();

        $this->cacheFlush('Invoice');

        service()->log('invoice', 'recurring', $this->user->id, ['invoice_id' => $this->row->id]);

        return $this->row;
    }


    /**
     * @return void
     */
    protected function values()
    {
        $this->row->date_at = $this->row->recurring_at;
        $this->row->recurring_at = $this->row->recurring->next($this->row->date_at);
        $this->row->required_at = null;
        $this->row->paid_at = null;

        $this->row->amount_paid = 0;
        $this->row->amount_due = $this->row->amount_total;

        $this->row->number = $this->row->serie->number_value;
    }

    /**
     * @return void
     */
    protected function status()
    {
        $default = Models\InvoiceStatus::select('id')
            ->byCompany($this->user->company)
            ->where('default', 1)
            ->first();

        if ($default) {
            $this->row->invoice_status_id = $default->id;
        }
    }

    /**
     * @param \Illuminate\Support\Collection $items
     *
     * @return void
     */
    protected function items(Collection $items)
    {
        Models\InvoiceItem::insert($items->each(function ($item) {
            $item->id = null;
            $item->invoice_id = $this->row->id;
        })->toArray());
    }

    /**
     * @return void
     */
    protected function file()
    {
        (new Domains\InvoiceFile\Store($this->user, ['main' => true]))->create($this->row);
    }

    /**
     * @return void
     */
    protected function configuration()
    {
        Domains\InvoiceSerie\StoreNumber::setNext($this->row->serie);
    }
}
