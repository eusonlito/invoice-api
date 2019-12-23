<?php declare(strict_types=1);

namespace App\Domains\Invoice\Store;

use App\Domains\InvoiceFile\Store as InvoiceFileStore;
use App\Domains\InvoiceSerie\Store as InvoiceSerieStore;
use App\Exceptions;
use App\Models;
use App\Models\Invoice as Model;

class Update extends StoreAbstract
{
    /**
     * @return \App\Models\Invoice
     */
    public function update(): Model
    {
        $this->check();

        $this->row->number = $this->data['number'];
        $this->row->date_at = $this->data['date_at'];
        $this->row->required_at = $this->data['required_at'];
        $this->row->paid_at = $this->data['paid_at'];
        $this->row->comment_public = $this->data['comment_public'];
        $this->row->comment_private = $this->data['comment_private'];

        $this->row->amount_paid = $this->float($this->data['amount_paid']);
        $this->row->amount_shipping = $this->float($this->data['amount_shipping']);

        $this->company();
        $this->clientAddressBilling();
        $this->clientAddressShipping();
        $this->recurring();
        $this->serie();
        $this->status();
        $this->payment();
        $this->discount();
        $this->tax();
        $this->shipping();

        $this->row->save();
        $this->row->load([
            'client', 'clientAddressBilling', 'clientAddressShipping', 'recurring',
            'serie', 'status', 'discount', 'payment', 'shipping', 'tax'
        ]);

        $this->items();
        $this->amount();

        $this->row->save();
        $this->row->load(['file', 'items']);

        $this->fileMain();
        $this->configuration();

        $this->cacheFlush();

        service()->log('invoice', 'update', $this->user->id, ['invoice_id' => $this->row->id]);

        return $this->row;
    }

    /**
     * @return void
     */
    protected function check()
    {
        $exists = Model::byCompany($this->user->company)
            ->where('id', '!=', $this->row->id)
            ->where('number', $this->data['number'])
            ->count();

        if ($exists) {
            throw new Exceptions\ValidatorException(__('validator.number-duplicated'));
        }
    }

    /**
     * @return void
     */
    protected function company()
    {
        $this->row->company_name = $this->user->company->name;
        $this->row->company_address = $this->user->company->address;
        $this->row->company_city = $this->user->company->city;
        $this->row->company_state = $this->user->company->state;
        $this->row->company_postal_code = $this->user->company->postal_code;
        $this->row->company_country = $this->user->company->country->name;
        $this->row->company_tax_number = $this->user->company->tax_number;
        $this->row->company_phone = $this->user->company->phone;
        $this->row->company_email = $this->user->company->email;
    }

    /**
     * @return void
     */
    protected function clientAddressBilling()
    {
        $address = Models\ClientAddress::byId($this->data['client_address_billing_id'])
            ->byCompany($this->user->company)
            ->firstOrFail();

        $this->row->client_id = $address->client_id;
        $this->row->client_address_billing_id = $address->id;
        $this->row->billing_name = $address->name;
        $this->row->billing_address = $address->address;
        $this->row->billing_city = $address->city;
        $this->row->billing_state = $address->state;
        $this->row->billing_postal_code = $address->postal_code;
        $this->row->billing_country = $address->country;
        $this->row->billing_tax_number = $address->tax_number;
    }

    /**
     * @return void
     */
    protected function clientAddressShipping()
    {
        if (empty($this->data['client_address_shipping_id'])) {
            return;
        }

        $address = Models\ClientAddress::byId($this->data['client_address_shipping_id'])
            ->where('client_id', $this->row->client_id)
            ->byCompany($this->user->company)
            ->firstOrFail();

        $this->row->client_address_shipping_id = $address->id;
        $this->row->shipping_name = $address->name;
        $this->row->shipping_address = $address->address;
        $this->row->shipping_city = $address->city;
        $this->row->shipping_state = $address->state;
        $this->row->shipping_postal_code = $address->postal_code;
        $this->row->shipping_country = $address->country;
    }

    /**
     * @return void
     */
    protected function recurring()
    {
        if (empty($this->data['invoice_recurring_id'])) {
            return $this->row->invoice_recurring_id = $this->row->recurring_at = null;
        }

        $related = Models\InvoiceRecurring::select('id', 'every')
            ->byId($this->data['invoice_recurring_id'])
            ->byCompany($this->user->company)
            ->firstOrFail();

        if ($related->id === $this->row->invoice_recurring_id) {
            return;
        }

        $this->row->invoice_recurring_id = $related->id;
        $this->row->recurring_at = $related->next($this->row->date_at);
    }

    /**
     * @return void
     */
    protected function serie()
    {
        $this->row->invoice_serie_id = Models\InvoiceSerie::select('id')
            ->byId($this->data['invoice_serie_id'])
            ->byCompany($this->user->company)
            ->firstOrFail()
            ->id;
    }

    /**
     * @return void
     */
    protected function status()
    {
        $this->row->invoice_status_id = Models\InvoiceStatus::select('id')
            ->byId($this->data['invoice_status_id'])
            ->byCompany($this->user->company)
            ->firstOrFail()
            ->id;
    }

    /**
     * @return void
     */
    protected function payment()
    {
        if (empty($this->data['payment_id'])) {
            return $this->row->payment_id = null;
        }

        $this->row->payment_id = Models\Payment::select('id')
            ->byId($this->data['payment_id'])
            ->byCompany($this->user->company)
            ->firstOrFail()
            ->id;
    }

    /**
     * @return void
     */
    protected function discount()
    {
        if (empty($this->data['discount_id'])) {
            return $this->row->discount_id = null;
        }

        $this->row->discount_id = Models\Discount::byId($this->data['discount_id'])
            ->byCompany($this->user->company)
            ->firstOrFail()
            ->id;
    }

    /**
     * @return void
     */
    protected function tax()
    {
        if (empty($this->data['tax_id'])) {
            return $this->row->tax_id = null;
        }

        $this->row->tax_id = Models\Tax::byId($this->data['tax_id'])
            ->byCompany($this->user->company)
            ->firstOrFail()
            ->id;
    }

    /**
     * @return void
     */
    protected function shipping()
    {
        if (empty($this->data['shipping_id'])) {
            return $this->row->shipping_id = null;
        }

        $this->row->shipping_id = Models\Shipping::byId($this->data['shipping_id'])
            ->byCompany($this->user->company)
            ->firstOrFail()
            ->id;
    }

    /**
     * @return void
     */
    protected function items()
    {
        $ids = array_filter(array_column($this->data['items'], 'id'));

        Models\InvoiceItem::whereNotIn('id', $ids)
            ->where('invoice_id', $this->row->id)
            ->delete();

        $items = Models\InvoiceItem::whereIn('id', $ids)
            ->where('invoice_id', $this->row->id)
            ->get()
            ->keyBy('id');

        $products = Models\Product::whereIn('reference', array_column($this->data['items'], 'reference'))
            ->byCompany($this->user->company)
            ->get()
            ->keyBy('reference');

        unset($this->data['items']['*']);

        foreach ($this->data['items'] as $i => $data) {
            if (empty($data['id']) || !($item = $items->get($data['id']))) {
                $item = new Models\InvoiceItem($data);
            }

            $this->item($i, $item->fill($data), $products->get($data['reference']));
        }

        $this->row->quantity = $this->row->items->sum('quantity');
    }

    /**
     * @param \App\Models\InvoiceItem $item
     * @param ?\App\Models\Product $product
     *
     * @return void
     */
    protected function item(int $line, Models\InvoiceItem $item, ?Models\Product $product)
    {
        $item->line = $line;
        $item->quantity = $this->float($item->quantity);
        $item->percent_discount = (int)$item->percent_discount;
        $item->percent_tax = $this->row->tax ? $this->float($this->row->tax->value) : 0;
        $item->amount_price = $this->float($item->amount_price);
        $item->amount_subtotal = 0;
        $item->amount_discount = 0;
        $item->amount_tax = 0;
        $item->amount_total = 0;
        $item->invoice_id = $this->row->id;
        $item->user_id = $this->row->user_id;
        $item->product_id = $product ? $product->id : null;

        if ($item->amount_price && $item->quantity) {
            $item->amount_subtotal = $this->float($item->amount_price * $item->quantity);
            $item->amount_discount = $this->float($item->amount_subtotal * $item->percent_discount / 100);
            $item->amount_subtotal = $this->float($item->amount_subtotal - $item->amount_discount);
            $item->amount_tax = $this->float($item->amount_subtotal * $item->percent_tax / 100);
            $item->amount_total = $this->float($item->amount_subtotal + $item->amount_tax);
        }

        $item->save();
    }

    /**
     * @return void
     */
    protected function amount()
    {
        $this->row->amount_subtotal = $this->float($this->row->items->sum('amount_subtotal'));

        $this->amountDiscount();
        $this->amountTax();
        $this->amountTotal();
        $this->amountPaidDue();
    }

    /**
     * @return void
     */
    protected function amountDiscount()
    {
        if (empty($this->row->discount)) {
            return $this->row->percent_discount = $this->row->amount_discount = 0;
        }

        $value = $this->float($this->row->discount->value);

        if ($this->row->discount->type === 'fixed') {
            $this->row->percent_discount = 0;
            $this->row->amount_discount = $value;
        } elseif ($this->row->discount->type === 'percent') {
            $this->row->percent_discount = $value;
            $this->row->amount_discount = $this->float($this->row->amount_subtotal * $this->row->percent_discount / 100);
        }
    }

    /**
     * @return void
     */
    protected function amountTax()
    {
        if (empty($this->row->tax)) {
            return $this->row->percent_tax = $this->row->amount_tax = 0;
        }

        $this->row->percent_tax = $this->float($this->row->tax->value);
        $this->row->amount_tax = $this->float($this->row->amount_subtotal * $this->row->percent_tax / 100);
    }

    /**
     * @return void
     */
    protected function amountTotal()
    {
        $this->row->amount_total = $this->float(
            $this->row->amount_subtotal
            - $this->row->amount_discount
            + $this->row->amount_tax
            + $this->row->amount_shipping
        );
    }

    /**
     * @return void
     */
    protected function amountPaidDue()
    {
        if ($this->row->amount_paid > $this->row->amount_total) {
            $this->row->amount_paid = $this->row->amount_total;
        }

        $this->row->amount_due = $this->float($this->row->amount_total - $this->row->amount_paid);
    }

    /**
     * @return void
     */
    protected function fileMain()
    {
        $store = new InvoiceFileStore($this->user, $this->row->file, ['main' => true]);

        if ($this->row->file) {
            $store->update();
        } else {
            $store->create($this->row);
        }
    }

    /**
     * @return void
     */
    protected function configuration()
    {
        (new InvoiceSerieStore($this->user, $this->row->serie))->numberNext();
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
