<?php declare(strict_types=1);

namespace App\Domains\InvoiceItem\Store;

use App\Models\InvoiceItem as Model;

class Update extends StoreAbstract
{
    /**
     * @return \App\Models\InvoiceItem
     */
    public function update(): Model
    {
        $this->row->line = (int)$this->data['line'];
        $this->row->reference = $this->data['reference'];
        $this->row->description = $this->data['description'];
        $this->row->quantity = $this->float($this->data['quantity']);
        $this->row->percent_discount = (int)$this->data['percent_discount'];
        $this->row->percent_tax = $this->float($this->data['percent_tax']);
        $this->row->amount_price = $this->float($this->data['amount_price']);
        $this->row->amount_subtotal = 0;
        $this->row->amount_discount = 0;
        $this->row->amount_tax = 0;
        $this->row->amount_total = 0;
        $this->row->product_id = $this->data['product_id'];

        if ($this->row->amount_price && $this->row->quantity) {
            $this->row->amount_subtotal = $this->float($this->row->amount_price * $this->row->quantity);
            $this->row->amount_discount = $this->float($this->row->amount_subtotal * $this->row->percent_discount / 100);
            $this->row->amount_subtotal = $this->float($this->row->amount_subtotal - $this->row->amount_discount);
            $this->row->amount_tax = $this->float($this->row->amount_subtotal * $this->row->percent_tax / 100);
            $this->row->amount_total = $this->float($this->row->amount_subtotal + $this->row->amount_tax);
        }

        $this->row->save();

        return $this->row;
    }

    /**
     * @param string|float $value
     *
     * @return float
     */
    protected function float($value): float
    {
        return round(abs((float)$value), 2);
    }
}
