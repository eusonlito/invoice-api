<?php declare(strict_types=1);

namespace Database\Fake;

use DateInterval;
use DateTime;
use Illuminate\Support\Collection;
use App\Domains\InvoiceSerie\StoreNumber;
use App\Models;
use App\Models\Invoice as Model;

class Invoice extends FakeAbstract
{
    /**
     * @var \App\Models\User
     */
    protected Models\User $user;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $client;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $discount;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $invoiceSerie;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $invoiceStatus;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $payment;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $product;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $shipping;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $tax;

    /**
     * @var int
     */
    protected int $number;

    /**
     * @return void
     */
    public function run()
    {
        $this->user = Models\User::with(['company'])->first();
        $this->client = Models\Client::with(['addresses'])->get();
        $this->discount = Models\Discount::get();
        $this->invoiceSerie = Models\InvoiceSerie::get();
        $this->invoiceStatus = Models\InvoiceStatus::get();
        $this->payment = Models\Payment::get();
        $this->product = Models\Product::get();
        $this->shipping = Models\Shipping::get();
        $this->tax = Models\Tax::get();
        $this->number = 1;

        $date = new DateTime('@'.strtotime('-'.rand(1000, 2000).' days'));
        $now = new DateTime();

        while ($date < $now) {
            $this->invoice($date->add(new DateInterval('P'.rand(1, 3).'D')));
        }
    }

    /**
     * @param \DateTime $date
     *
     * @return void
     */
    protected function invoice(DateTime $date): void
    {
        $faker = $this->faker();

        $client = $this->client->random();
        $billing = $client->addresses->random();
        $invoiceSerie = $this->invoiceSerie->random();
        $shipping = $client->addresses->random();
        $tax = $this->tax->random();

        $row = factory(Model::class)->create([
            'number' => $this->number($invoiceSerie),

            'company_name' => $this->user->company->name,
            'company_address' => $this->user->company->address,
            'company_city' => $this->user->company->city,
            'company_state' => $this->user->company->state,
            'company_postal_code' => $this->user->company->postal_code,
            'company_country' => $this->user->company->country->name,
            'company_tax_number' => $this->user->company->tax_number,
            'company_phone' => $this->user->company->phone,
            'company_email' => $this->user->company->email,

            'billing_name' => $billing->name,
            'billing_address' => $billing->address,
            'billing_city' => $billing->city,
            'billing_state' => $billing->state,
            'billing_postal_code' => $billing->postal_code,
            'billing_country' => $billing->country,
            'billing_tax_number' => $billing->tax_number,

            'shipping_name' => $shipping->name,
            'shipping_address' => $shipping->address,
            'shipping_city' => $shipping->city,
            'shipping_state' => $shipping->state,
            'shipping_postal_code' => $shipping->postal_code,
            'shipping_country' => $shipping->country,

            'date_at' => $date->format('Y-m-d'),

            'company_id' => 1,
            'client_id' => $client->id,
            'client_address_billing_id' => $billing->id,
            'client_address_shipping_id' => $shipping->id,
            'invoice_serie_id' => $invoiceSerie->id,
            'user_id' => 1,
        ]);

        $items = collect();

        for ($i = 0, $max = rand(2, 4); $i < $max; ++$i) {
            $items->push($this->item($row, $i, $tax));
        }

        $this->amount($date, $row, $items, $tax);

        StoreNumber::setNext($invoiceSerie);
    }

    /**
     * @param \App\Models\InvoiceSerie $invoiceSerie
     *
     * @return string
     */
    protected function number(Models\InvoiceSerie $invoiceSerie): string
    {
        return $invoiceSerie->number_prefix.sprintf('%0'.$invoiceSerie->number_fill.'d', $invoiceSerie->number_next);
    }

    /**
     * @param \App\Models\Invoice $row
     * @param int $line
     * @param \App\Models\Tax $tax
     *
     * @return \App\Models\InvoiceItem
     */
    protected function item(Model $row, int $line, Models\Tax $tax): Models\InvoiceItem
    {
        $product = $this->product->random();
        $quantity = rand(1, 3);
        $discount = rand(0, 50);

        $amount_subtotal = $product->price * $quantity;
        $amount_discount = $amount_subtotal * $discount / 100;
        $amount_subtotal -= $amount_discount;
        $amount_tax = $amount_subtotal * $tax->value / 100;

        return factory(Models\InvoiceItem::class)->create([
            'line' => $line,

            'reference' => $product->reference,
            'description' => $product->name,

            'quantity' => $quantity,
            'percent_discount' => $discount,

            'percent_tax' => $tax->value,

            'amount_price' => $product->price,
            'amount_discount' => $amount_discount,
            'amount_tax' => $amount_tax,
            'amount_subtotal' => $amount_subtotal,
            'amount_total' => ($amount_subtotal + $amount_tax),

            'invoice_id' => $row->id,
            'product_id' => $product->id,
            'user_id' => 1,
        ]);
    }

    /**
     * @param \DateTime $date
     * @param \App\Models\Invoice $row
     * @param \Illuminate\Support\Collection $items
     * @param \App\Models\Tax $tax
     *
     * @return void
     */
    protected function amount(DateTime $date, Model $row, Collection $items, Models\Tax $tax)
    {
        $discount = $this->discount->random();
        $payment = $this->payment->random();
        $shipping = $this->shipping->random();
        $status = $this->invoiceStatus->random();

        $row->comment_public = $payment->description;

        $row->quantity = $items->sum('quantity');
        $row->percent_tax = $tax->value;
        $row->amount_subtotal = $items->sum('amount_subtotal');

        if ($discount->type === 'percent') {
            $row->percent_discount = $discount->value;
            $row->amount_discount = $row->amount_subtotal * $discount->value / 100;
        } else {
            $row->percent_discount = 0;
            $row->amount_discount = $discount->value;
        }

        $row->amount_tax = $items->sum('amount_tax');
        $row->amount_shipping = $shipping->value;
        $row->amount_total = $row->amount_subtotal + $row->amount_tax + $row->amount_shipping - $row->amount_discount;

        if ($status->paid) {
            $row->amount_paid = $row->amount_total;
            $row->paid_at = (clone $date)->add(new DateInterval('P'.rand(10, 60).'D'));
        } else {
            $row->amount_paid = rand(0, (int)$row->amount_total);
            $row->paid_at = null;
        }

        $row->amount_due = $row->amount_total - $row->amount_paid;

        $row->discount_id = $discount->id;
        $row->invoice_status_id = $status->id;
        $row->payment_id = $payment->id;
        $row->shipping_id = $shipping->id;
        $row->tax_id = $tax->id;

        $row->save();
    }
}
