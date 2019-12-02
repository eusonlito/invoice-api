<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Models;
use App\Models\Invoice as Model;

class InvoiceTest extends TestAbstract
{
    /**
     * @var string
     */
    protected string $route = 'invoice';

    /**
     * @var int
     */
    protected int $count = 0;

    /**
     * @var array
     */
    protected array $structure = [
        'id', 'number', 'billing_name', 'date_at', 'paid_at', 'created_at', 'company_name',
        'company_city', 'company_state', 'company_country', 'billing_city', 'billing_state',
        'billing_country', 'shipping_name', 'shipping_city', 'shipping_state', 'quantity',
        'percent_tax', 'amount_subtotal', 'amount_tax', 'required_at', 'comment_public',
        'clientAddressBilling' => ['id', 'name'],
        'clientAddressShipping' => ['id', 'name'],
        'discount' => ['id', 'name'],
        'payment' => ['id', 'name'],
        'recurring' => ['id', 'name'],
        'serie' => ['id', 'name'],
        'status' => ['id', 'name'],
        'shipping' => ['id', 'name'],
        'tax' => ['id', 'name'],
    ];

    /**
     * @var \App\Models\Discount
     */
    protected Models\Discount $discount;

    /**
     * @var \App\Models\Shipping
     */
    protected Models\Shipping $shipping;

    /**
     * @var \App\Models\Tax
     */
    protected Models\Tax $tax;

    /**
     * @return void
     */
    public function testIndexNoAuthFail(): void
    {
        $this->json('GET', $this->route('index'))
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testIndexSuccess(): void
    {
        $this->auth()
            ->json('GET', $this->route('index'))
            ->assertStatus(200)
            ->assertJsonCount($this->count);
    }

    /**
     * @return void
     */
    public function testExportNoAuthFail(): void
    {
        $this->json('GET', $this->route('export'))
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testExportSuccess(): void
    {
        $this->auth()
            ->json('GET', $this->route('export'))
            ->assertStatus(200)
            ->assertJsonCount($this->count);
    }

    /**
     * @return void
     */
    public function testCreateNoAuthFail(): void
    {
        $this->json('POST', $this->route('create'))
            ->assertStatus(401);
    }

    /**
     * Rules:
     *
     * 'client_address_billing_id' => 'required|integer',
     * 'client_address_shipping_id' => 'nullable|integer',
     * 'number' => 'required',
     * 'date_at' => 'required|date',
     * 'paid_at' => 'nullable|date',
     * 'required_at' => 'nullable|date',
     * 'invoice_recurring_id' => 'nullable|integer',
     * 'invoice_serie_id' => 'required|integer',
     * 'invoice_status_id' => 'required|integer',
     * 'payment_id' => 'nullable|integer',
     * 'discount_id' => 'nullable|integer',
     * 'tax_id' => 'nullable|integer',
     * 'shipping_id' => 'nullable|integer',
     * 'percent_discount' => 'numeric',
     * 'percent_tax' => 'numeric',
     * 'amount_due' => 'numeric',
     * 'amount_shipping' => 'numeric',
     * 'amount_paid' => 'numeric',
     * 'comment_public' => 'string',
     * 'comment_private' => 'string',
     * 'items' => 'required|array',
     * 'items.*.reference' => 'string',
     * 'items.*.description' => 'required',
     * 'items.*.amount_price' => 'numeric',
     * 'items.*.quantity' => 'required|numeric',
     * 'items.*.percent_discount' => 'integer',
     */

    /**
     * @return void
     */
    public function testCreateEmptyFail(): void
    {
        $this->auth()
            ->json('POST', $this->route('create'))
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertSee($this->t('validator.client_address_billing_id-required'))
            ->assertSee($this->t('validator.number-required'))
            ->assertSee($this->t('validator.date_at-required'))
            ->assertSee($this->t('validator.invoice_serie_id-required'))
            ->assertSee($this->t('validator.invoice_status_id-required'))
            ->assertSee($this->t('validator.items-required'));
    }

    /**
     * @return void
     */
    public function testCreateItemsEmptyFail(): void
    {
        $fail = ['items' => [[]]];

        $this->auth()
            ->json('POST', $this->route('create'), $fail)
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertSee($this->t('validator.description-required'))
            ->assertSee($this->t('validator.quantity-required'));
    }

    /**
     * @return void
     */
    public function testCreateInvalidFail(): void
    {
        $fail = [
            'date_at' => 'fail',
            'paid_at' => 'fail',
            'required_at' => 'fail',
            'percent_discount' => 'fail',
            'percent_tax' => 'fail',
            'amount_due' => 'fail',
            'amount_shipping' => 'fail',
            'amount_paid' => 'fail',

            'client_address_billing_id' => 'fail',
            'client_address_shipping_id' => 'fail',
            'invoice_recurring_id' => 'fail',
            'invoice_serie_id' => 'fail',
            'invoice_status_id' => 'fail',
            'payment_id' => 'fail',
            'discount_id' => 'fail',
            'tax_id' => 'fail',
            'shipping_id' => 'fail',
        ];

        $this->auth()
            ->json('POST', $this->route('create'), $fail)
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertSee($this->t('validator.date_at-date'))
            ->assertSee($this->t('validator.paid_at-date'))
            ->assertSee($this->t('validator.required_at-date'))
            ->assertSee($this->t('validator.percent_discount-numeric'))
            ->assertSee($this->t('validator.percent_tax-numeric'))
            ->assertSee($this->t('validator.amount_due-numeric'))
            ->assertSee($this->t('validator.amount_shipping-numeric'))
            ->assertSee($this->t('validator.amount_paid-numeric'))
            ->assertSee($this->t('validator.client_address_billing_id-integer'))
            ->assertSee($this->t('validator.client_address_shipping_id-integer'))
            ->assertSee($this->t('validator.invoice_recurring_id-integer'))
            ->assertSee($this->t('validator.invoice_serie_id-integer'))
            ->assertSee($this->t('validator.invoice_status_id-integer'))
            ->assertSee($this->t('validator.payment_id-integer'))
            ->assertSee($this->t('validator.discount_id-integer'))
            ->assertSee($this->t('validator.tax_id-integer'))
            ->assertSee($this->t('validator.shipping_id-integer'));
    }

    /**
     * @return void
     */
    public function testCreateItemsInvalidFail(): void
    {
        $fail = [
            'items' => [
                [
                    'amount_price' => 'fail',
                    'quantity' => 'fail',
                    'percent_discount' => 'fail'
                ]
            ]
        ];

        $this->auth()
            ->json('POST', $this->route('create'), $fail)
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertSee($this->t('validator.amount_price-numeric'))
            ->assertSee($this->t('validator.quantity-numeric'))
            ->assertSee($this->t('validator.percent_discount-integer'));
    }

    /**
     * @return void
     */
    public function testCreateFirstSuccess(): void
    {
        $row = factory(Model::class)->make();
        $row->user = $this->userFirst();

        $row = $this->data($row);
        $items = [$this->item($row, 2, 10), $this->item($row, 4, 12)];

        $this->auth($row->user)
            ->json('POST', $this->route('create'), ['items' => $items] + $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure)
            ->assertJson([
                'quantity' => 6,
                'percent_discount' => 17.0,
                'percent_tax' => 19.0,
                'amount_subtotal' => 56.44,
                'amount_discount' => 9.59,
                'amount_tax' => 10.72,
                'amount_shipping' => 10.0,
                'amount_total' => 67.57,
                'amount_paid' => 20.0,
                'amount_due' => 47.57,
            ]);
    }

    /**
     * @return void
     */
    public function testCreateSuccess(): void
    {
        $row = factory(Model::class)->make();
        $row->user = $this->user();

        $row = $this->data($row);
        $items = [$this->item($row, 4, 20), $this->item($row, 5, 24)];

        $this->auth($row->user)
            ->json('POST', $this->route('create'), ['items' => $items] + $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure)
            ->assertJson([
                'quantity' => 9,
                'percent_discount' => 15.0,
                'percent_tax' => 21.0,
                'amount_subtotal' => 170.0,
                'amount_discount' => 25.5,
                'amount_tax' => 35.7,
                'amount_shipping' => 8.0,
                'amount_total' => 188.2,
                'amount_paid' => 20.0,
                'amount_due' => 168.2,
            ]);
    }

    /**
     * @return void
     */
    public function testDetailNoAuthFail(): void
    {
        $this->json('GET', $this->route('detail', $this->row()->id))
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testDetailNotAllowedFail(): void
    {
        $this->auth($this->userFirst())
            ->json('GET', $this->route('detail', $this->row()->id))
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testDetailSuccess(): void
    {
        $this->auth()
            ->json('GET', $this->route('detail', $this->row()->id))
            ->assertStatus(200)
            ->assertJsonStructure($this->structure);
    }

    /**
     * @return void
     */
    public function testUpdateEmptyFail(): void
    {
        $this->auth()
            ->json('PATCH', $this->route('update', $this->row()->id))
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertSee($this->t('validator.client_address_billing_id-required'))
            ->assertSee($this->t('validator.number-required'))
            ->assertSee($this->t('validator.date_at-required'))
            ->assertSee($this->t('validator.invoice_serie_id-required'))
            ->assertSee($this->t('validator.invoice_status_id-required'))
            ->assertSee($this->t('validator.items-required'));
    }

    /**
     * @return void
     */
    public function testUpdateItemsEmptyFail(): void
    {
        $fail = ['items' => [[]]];

        $this->auth()
            ->json('PATCH', $this->route('update', $this->row()->id), $fail)
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertSee($this->t('validator.description-required'))
            ->assertSee($this->t('validator.quantity-required'));
    }

    /**
     * @return void
     */
    public function testUpdateInvalidFail(): void
    {
        $fail = [
            'date_at' => 'fail',
            'paid_at' => 'fail',
            'required_at' => 'fail',
            'percent_discount' => 'fail',
            'percent_tax' => 'fail',
            'amount_due' => 'fail',
            'amount_shipping' => 'fail',
            'amount_paid' => 'fail',

            'client_address_billing_id' => 'fail',
            'client_address_shipping_id' => 'fail',
            'invoice_recurring_id' => 'fail',
            'invoice_serie_id' => 'fail',
            'invoice_status_id' => 'fail',
            'payment_id' => 'fail',
            'discount_id' => 'fail',
            'tax_id' => 'fail',
            'shipping_id' => 'fail',
        ];

        $this->auth()
            ->json('PATCH', $this->route('update', $this->row()->id), $fail)
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertSee($this->t('validator.date_at-date'))
            ->assertSee($this->t('validator.paid_at-date'))
            ->assertSee($this->t('validator.required_at-date'))
            ->assertSee($this->t('validator.percent_discount-numeric'))
            ->assertSee($this->t('validator.percent_tax-numeric'))
            ->assertSee($this->t('validator.amount_due-numeric'))
            ->assertSee($this->t('validator.amount_shipping-numeric'))
            ->assertSee($this->t('validator.amount_paid-numeric'))
            ->assertSee($this->t('validator.client_address_billing_id-integer'))
            ->assertSee($this->t('validator.client_address_shipping_id-integer'))
            ->assertSee($this->t('validator.invoice_recurring_id-integer'))
            ->assertSee($this->t('validator.invoice_serie_id-integer'))
            ->assertSee($this->t('validator.invoice_status_id-integer'))
            ->assertSee($this->t('validator.payment_id-integer'))
            ->assertSee($this->t('validator.discount_id-integer'))
            ->assertSee($this->t('validator.tax_id-integer'))
            ->assertSee($this->t('validator.shipping_id-integer'));
    }

    /**
     * @return void
     */
    public function testUpdateItemsInvalidFail(): void
    {
        $fail = [
            'items' => [
                [
                    'amount_price' => 'fail',
                    'quantity' => 'fail',
                    'percent_discount' => 'fail'
                ]
            ]
        ];

        $this->auth()
            ->json('PATCH', $this->route('update', $this->row()->id), $fail)
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertSee($this->t('validator.amount_price-numeric'))
            ->assertSee($this->t('validator.quantity-numeric'))
            ->assertSee($this->t('validator.percent_discount-integer'));
    }

    /**
     * @return void
     */
    public function testUpdateNoAuthFail(): void
    {
        $this->json('PATCH', $this->route('update', $this->row()->id))
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testUpdateNotAllowedFail(): void
    {
        $row = $this->row();
        $row->load(['items']);

        $this->auth($this->userFirst())
            ->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testUpdateSuccess(): void
    {
        $row = $this->row();
        $row->load(['items']);

        $this->auth()
            ->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure);
    }

    /**
     * @return void
     */
    public function testIndexAfterSuccess(): void
    {
        $this->auth()
            ->json('GET', $this->route('index'))
            ->assertStatus(200)
            ->assertJsonCount($this->count + 1);
    }

    /**
     * @return void
     */
    public function testExportAfterSuccess(): void
    {
        $this->auth()
            ->json('GET', $this->route('export'))
            ->assertStatus(200)
            ->assertJsonCount($this->count + 1);
    }

    /**
     * @return \App\Models\Invoice
     */
    protected function row(): Model
    {
        return Model::orderBy('id', 'DESC')->first();
    }

    /**
     * @param \App\Models\Invoice $row
     *
     * @return \App\Models\Invoice
     */
    protected function data(Model $row): Model
    {
        $user = $row->user;

        $invoiceRecurring = Models\InvoiceRecurring::where('user_id', $user->id)->orderBy('id', 'DESC')->first();
        $invoiceSerie = Models\InvoiceSerie::where('user_id', $user->id)->orderBy('id', 'DESC')->first();
        $invoiceStatus = Models\InvoiceStatus::where('user_id', $user->id)->orderBy('id', 'DESC')->first();
        $client = Models\Client::where('user_id', $user->id)->orderBy('id', 'DESC')->first();
        $clientBilling = $client->addresses->random();
        $clientShipping = $client->addresses->random();
        $discount = $this->discount($user);
        $payment = Models\Payment::where('user_id', $user->id)->orderBy('id', 'DESC')->first();
        $shipping = $this->shipping($user);
        $tax = $this->tax($user);

        $row->number = 'T001';

        $row->company_name = $user->company->name;
        $row->company_address = $user->company->address;
        $row->company_city = $user->company->city;
        $row->company_state = $user->company->state;
        $row->company_postal_code = $user->company->postal_code;
        $row->company_country = $user->company->country->name;
        $row->company_tax_number = $user->company->tax_number;
        $row->company_phone = $user->company->phone;
        $row->company_email = $user->company->email;

        $row->billing_name = $clientBilling->name;
        $row->billing_address = $clientBilling->address;
        $row->billing_city = $clientBilling->city;
        $row->billing_state = $clientBilling->state;
        $row->billing_postal_code = $clientBilling->postal_code;
        $row->billing_country = $clientBilling->country;
        $row->billing_tax_number = $clientBilling->tax_number;

        $row->shipping_name = $clientShipping->name;
        $row->shipping_address = $clientShipping->address;
        $row->shipping_city = $clientShipping->city;
        $row->shipping_state = $clientShipping->state;
        $row->shipping_postal_code = $clientShipping->postal_code;
        $row->shipping_country = $clientShipping->country;

        $row->date_at = date('Y-m-d');

        $row->percent_discount = $discount->value;
        $row->percent_tax = $tax->value;
        $row->amount_shipping = $shipping->value;
        $row->amount_paid = 20;

        $row->client_id = $client->id;
        $row->client_address_billing_id = $clientBilling->id;
        $row->client_address_shipping_id = $clientShipping->id;
        $row->discount_id = $discount->id;
        $row->invoice_recurring_id = $invoiceRecurring->id;
        $row->invoice_serie_id = $invoiceSerie->id;
        $row->invoice_status_id = $invoiceStatus->id;
        $row->payment_id = $payment->id;
        $row->shipping_id = $shipping->id;
        $row->tax_id = $tax->id;

        return $row;
    }

    /**
     * @param \App\Models\Invoice $row
     * @param int $quantity
     * @param float $price
     *
     * @return array
     */
    protected function item(Model $row, int $quantity, float $price): array
    {
        $user = $row->user;

        $product = Models\Product::where('user_id', $user->id)->orderBy('id', 'DESC')->first();

        $discount = $this->discount($user);
        $tax = $this->tax($user);

        $amount_subtotal = $price * $quantity;
        $amount_discount = $amount_subtotal * $discount->value / 100;
        $amount_subtotal -= $amount_discount;
        $amount_tax = $amount_subtotal * $tax->value / 100;

        return [
            'reference' => $product->reference,
            'description' => $product->name,

            'quantity' => $quantity,
            'percent_discount' => $discount->value,

            'percent_tax' => $tax->value,

            'amount_price' => $price,
            'amount_discount' => $amount_discount,
            'amount_tax' => $amount_tax,
            'amount_subtotal' => $amount_subtotal,
            'amount_total' => ($amount_subtotal + $amount_tax),

            'product_id' => $product->id,
        ];
    }

    /**
     * @param \App\Models\User $user
     *
     * @return \App\Models\Discount
     */
    protected function discount(Models\User $user): Models\Discount
    {
        return $this->discount ?? ($this->discount = Models\Discount::where('user_id', $user->id)->orderBy('id', 'DESC')->first());
    }

    /**
     * @param \App\Models\User $user
     *
     * @return \App\Models\Shipping
     */
    protected function shipping(Models\User $user): Models\Shipping
    {
        return $this->shipping ?? ($this->shipping = Models\Shipping::where('user_id', $user->id)->orderBy('id', 'DESC')->first());
    }

    /**
     * @param \App\Models\User $user
     *
     * @return \App\Models\Tax
     */
    protected function tax(Models\User $user): Models\Tax
    {
        return $this->tax ?? ($this->tax = Models\Tax::where('user_id', $user->id)->orderBy('id', 'DESC')->first());
    }
}