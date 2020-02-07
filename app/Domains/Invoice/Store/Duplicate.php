<?php declare(strict_types=1);

namespace App\Domains\Invoice\Store;

use Illuminate\Support\Collection;
use App\Domains\InvoiceFile\Store as InvoiceFileStore;
use App\Domains\InvoiceSerie\Store as InvoiceSerieStore;
use App\Models;
use App\Models\Invoice as Model;

class Duplicate extends StoreAbstract
{
    /**
     * @return \App\Models\Invoice
     */
    public function duplicate(): Model
    {
        $previous = clone $this->row;

        $this->row = new Model($previous->withoutRelations()->toArray());

        $this->serie();
        $this->status();
        $this->values();

        $this->row->save();

        $this->items($previous->items);

        $this->file();
        $this->configuration();

        $this->row->save();

        $this->cacheFlush();

        service()->log('invoice', 'duplicate', $this->user->id, ['invoice_id' => $this->row->id]);

        return $this->row;
    }

    /**
     * @return void
     */
    protected function serie()
    {
        $this->row->invoice_serie_id = Models\InvoiceSerie::select('id')
            ->byCompany($this->user->company)
            ->findOrFail($this->data['invoice_serie_id'])
            ->id;
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
     * @return void
     */
    protected function values()
    {
        $this->row->date_at = date('Y-m-d');
        $this->row->required_at = null;
        $this->row->paid_at = null;

        $this->row->amount_paid = 0;
        $this->row->amount_due = $this->row->amount_total;

        $this->row->number = $this->row->serie->number_value;
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
        (new InvoiceFileStore($this->request, $this->user, null, ['main' => true]))->create($this->row);
    }

    /**
     * @return void
     */
    protected function configuration()
    {
        (new InvoiceSerieStore($this->request, $this->user, $this->row->serie, ['main' => true]))->numberNext();
    }
}
