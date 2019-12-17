<?php declare(strict_types=1);

namespace App\Domains\InvoiceSerie\Store;

use App\Models;
use App\Models\InvoiceSerie as Model;

class Number extends StoreAbstract
{
    /**
     * @return \App\Models\InvoiceSerie
     */
    public function setNext(): Model
    {
        if ($this->row->number_prefix) {
            $this->row->number_next = $this->nextWithPrefix();
        } else {
            $this->row->number_next = $this->nextWithoutPrefix();
        }

        $this->row->save();

        return $this->row;
    }

    /**
     * @return int
     */
    protected function nextWithPrefix(): int
    {
        $number = Models\Invoice::byCompany($this->row->company)
            ->where('number', 'LIKE', $this->row->number_prefix.'%')
            ->where('invoice_serie_id', $this->row->id)
            ->orderBy('number', 'DESC')
            ->first()
            ->number ?? 0;

        return (int)preg_replace('/^'.preg_quote($this->row->number_prefix, '/').'/', '', (string)$number) + 1;
    }

    /**
     * @return int
     */
    protected function nextWithoutPrefix(): int
    {
        $number = Models\Invoice::byCompany($this->row->company)
            ->where('invoice_serie_id', $this->row->id)
            ->orderBy('number', 'DESC')
            ->first()
            ->number ?? 0;

        return $number + 1;
    }
}
