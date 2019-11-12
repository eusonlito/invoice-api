<?php declare(strict_types=1);

namespace App\Services\Model\Invoice;

use App\Models;
use App\Models\Invoice as Model;
use App\Services;
use App\Services\Model\StoreAbstract;

class Store extends StoreAbstract
{
    /**
     * @var \App\Models\Invoice
     */
    protected Model $invoice;

    /**
     * @return \App\Models\Invoice
     */
    public function create(): Model
    {
        $row = new Model([
            'company_id' => $this->user->company_id,
            'user_id' => $this->user->id,
        ]);

        return $this->update($row);
    }

    /**
     * @param \App\Models\Invoice $row
     *
     * @return \App\Models\Invoice
     */
    public function update(Model $row): Model
    {
        $this->invoice = $row;

        $this->invoice->number = $this->data['number'];
        $this->invoice->date_at = $this->data['date_at'];
        $this->invoice->required_at = $this->data['required_at'];
        $this->invoice->paid_at = $this->data['paid_at'];
        $this->invoice->comment_public = $this->data['comment_public'];
        $this->invoice->comment_private = $this->data['comment_private'];

        $this->invoice->amount_paid = $this->float($this->data['amount_paid']);
        $this->invoice->amount_shipping = $this->float($this->data['amount_shipping']);

        $this->company();
        $this->clientAddressBilling();
        $this->clientAddressShipping();
        $this->status();
        $this->payment();
        $this->discount();
        $this->tax();
        $this->shipping();

        $this->invoice->save();
        $this->invoice->load(['discount', 'shipping', 'tax']);

        $this->items();
        $this->amount();

        $this->invoice->save();
        $this->invoice->load(['file', 'items']);

        $this->fileMain();
        $this->configuration();

        $this->cacheFlush('Invoice');

        service()->log('invoice', 'update', $this->user->id, ['invoice_id' => $this->invoice->id]);

        return $this->invoice;
    }

    /**
     * @return void
     */
    protected function company()
    {
        $this->invoice->company_name = $this->user->company->name;
        $this->invoice->company_address = $this->user->company->address;
        $this->invoice->company_city = $this->user->company->city;
        $this->invoice->company_state = $this->user->company->state->name;
        $this->invoice->company_postal_code = $this->user->company->postal_code;
        $this->invoice->company_country = $this->user->company->state->country->name;
        $this->invoice->company_tax_number = $this->user->company->tax_number;
        $this->invoice->company_phone = $this->user->company->phone;
        $this->invoice->company_email = $this->user->company->email;
    }

    /**
     * @return void
     */
    protected function clientAddressBilling()
    {
        $address = Models\ClientAddress::byId($this->data['client_address_billing_id'])
            ->byCompany($this->user->company)
            ->firstOrFail();

        $this->invoice->client_id = $address->client_id;
        $this->invoice->client_address_billing_id = $address->id;
        $this->invoice->billing_name = $address->name;
        $this->invoice->billing_address = $address->address;
        $this->invoice->billing_city = $address->city;
        $this->invoice->billing_state = $address->state;
        $this->invoice->billing_postal_code = $address->postal_code;
        $this->invoice->billing_country = $address->country;
        $this->invoice->billing_tax_number = $address->tax_number;
    }

    /**
     * @return void
     */
    protected function clientAddressShipping()
    {
        if (empty($this->data['client_address_shipping_id'])) {
            return;
        }

        $address = Models\ClientAddress::byId($this->data['client_address_billing_id'])
            ->where('client_id', $this->invoice->client_id)
            ->byCompany($this->user->company)
            ->firstOrFail();

        $this->invoice->client_address_shipping_id = $address->id;
        $this->invoice->shipping_name = $address->name;
        $this->invoice->shipping_address = $address->address;
        $this->invoice->shipping_city = $address->city;
        $this->invoice->shipping_state = $address->state;
        $this->invoice->shipping_postal_code = $address->postal_code;
        $this->invoice->shipping_country = $address->country;
    }

    /**
     * @return void
     */
    protected function status()
    {
        $this->invoice->invoice_status_id = Models\InvoiceStatus::select('id')
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
            return $this->invoice->payment_id = null;
        }

        $this->invoice->payment_id = Models\Payment::select('id')
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
            return $this->invoice->discount_id = null;
        }

        $this->invoice->discount_id = Models\Discount::byId($this->data['discount_id'])
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
            return $this->invoice->tax_id = null;
        }

        $this->invoice->tax_id = Models\Tax::byId($this->data['tax_id'])
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
            return $this->invoice->shipping_id = null;
        }

        $this->invoice->shipping_id = Models\Shipping::byId($this->data['shipping_id'])
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
            ->where('invoice_id', $this->invoice->id)
            ->delete();

        $items = Models\InvoiceItem::whereIn('id', $ids)
            ->where('invoice_id', $this->invoice->id)
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
        $item->percent_discount = $this->float($item->percent_discount);
        $item->percent_tax = $this->invoice->tax ? $this->float($this->invoice->tax->value) : 0;
        $item->amount_price = $this->float($item->amount_price);
        $item->amount_subtotal = 0;
        $item->amount_discount = 0;
        $item->amount_tax = 0;
        $item->amount_total = 0;
        $item->invoice_id = $this->invoice->id;
        $item->user_id = $this->invoice->user_id;
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
        $this->invoice->amount_subtotal = $this->float($this->invoice->items->sum('amount_subtotal'));

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
        if (empty($this->invoice->discount)) {
            return $this->invoice->percent_discount = $this->invoice->amount_discount = 0;
        }

        $value = $this->float($this->invoice->discount->value);

        if ($this->invoice->discount->type === 'fixed') {
            $this->invoice->percent_discount = 0;
            $this->invoice->amount_discount = $value;
        } elseif ($this->invoice->discount->type === 'percent') {
            $this->invoice->percent_discount = $value;
            $this->invoice->amount_discount = $this->float($this->invoice->amount_subtotal * $this->invoice->percent_discount / 100);
        }
    }

    /**
     * @return void
     */
    protected function amountTax()
    {
        if (empty($this->invoice->tax)) {
            return $this->invoice->percent_tax = $this->invoice->amount_tax = 0;
        }

        $this->invoice->percent_tax = $this->float($this->invoice->tax->value);
        $this->invoice->amount_tax = $this->float($this->invoice->amount_subtotal * $this->invoice->percent_tax / 100);
    }

    /**
     * @return void
     */
    protected function amountTotal()
    {
        $this->invoice->amount_total = $this->float(
            $this->invoice->amount_subtotal
            - $this->invoice->amount_discount
            + $this->invoice->amount_tax
            + $this->invoice->amount_shipping
        );
    }

    /**
     * @return void
     */
    protected function amountPaidDue()
    {
        if ($this->invoice->amount_paid > $this->invoice->amount_total) {
            $this->invoice->amount_paid = $this->invoice->amount_total;
        }

        $this->invoice->amount_due = $this->float($this->invoice->amount_total - $this->invoice->amount_paid);
    }

    /**
     * @return void
     */
    protected function fileMain()
    {
        $service = new Services\Model\InvoiceFile\Store($this->user, ['main' => true]);

        if ($this->invoice->file) {
            $service->update($this->invoice->file);
        } else {
            $service->create($this->invoice);
        }
    }

    /**
     * @return void
     */
    protected function configuration()
    {
        Services\Model\InvoiceConfiguration\StoreNumber::setNext($this->user);
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
