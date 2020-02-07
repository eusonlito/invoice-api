<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Models;
use App\Models\Client as Model;

class ClientTest extends TestAbstract
{
    /**
     * @var string
     */
    protected string $route = 'client';

    /**
     * @var int
     */
    protected int $count = 0;

    /**
     * @var array
     */
    protected array $structure = [
        'id', 'name', 'phone', 'email', 'contact_name', 'contact_surname',
        'created_at', 'web', 'tax_number', 'type', 'contact_phone', 'contact_email',
        'comment', 'discount', 'payment', 'shipping', 'tax'
    ];

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
     * Rules:
     *
     * 'name' => 'required|string',
     * 'phone' => 'string',
     * 'email' => 'email|string',
     * 'contact_name' => 'string',
     * 'contact_surname' => 'string',
     * 'web' => 'string',
     * 'tax_number' => 'required|in:company,freelance',
     * 'type' => 'required|string',
     * 'contact_phone' => 'string',
     * 'contact_email' => 'email|string',
     * 'comment' => 'string',
     * 'discount_id' => 'nullable|integer',
     * 'payment_id' => 'nullable|integer',
     * 'shipping_id' => 'nullable|integer',
     * 'tax_id' => 'nullable|integer',
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
            ->assertDontSee(' name ')
            ->assertDontSee(' tax number ')
            ->assertDontSee(' type ')
            ->assertSee($this->t('validator.name-required'))
            ->assertSee($this->t('validator.tax_number-required'))
            ->assertSee($this->t('validator.type-required'));
    }

    /**
     * @return void
     */
    public function testCreateInvalidFail(): void
    {
        $fail = [
            'email' => 'fail',
            'contact_email' => 'fail',
            'discount_id' => 'fail',
            'payment_id' => 'fail',
            'shipping_id' => 'fail',
            'tax_id' => 'fail',
            'type' => 'fail'
        ];

        $this->auth()
            ->json('POST', $this->route('create'), $fail)
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' email ')
            ->assertDontSee(' contact email ')
            ->assertDontSee(' discount id ')
            ->assertDontSee(' payment id ')
            ->assertDontSee(' shipping id ')
            ->assertDontSee(' tax id ')
            ->assertDontSee(' type ')
            ->assertSee($this->t('validator.email-email'))
            ->assertSee($this->t('validator.contact_email-email'))
            ->assertSee($this->t('validator.discount_id-integer'))
            ->assertSee($this->t('validator.payment_id-integer'))
            ->assertSee($this->t('validator.shipping_id-integer'))
            ->assertSee($this->t('validator.tax_id-integer'))
            ->assertSee($this->t('validator.type-in'));
    }

    /**
     * @return void
     */
    public function testCreateDiscountNotAllowedFail(): void
    {
        $row = factory(Model::class)->make(['discount_id' => 1]);

        $this->auth()
            ->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testCreatePaymentNotAllowedFail(): void
    {
        $row = factory(Model::class)->make(['payment_id' => 1]);

        $this->auth()
            ->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testCreateShippingNotAllowedFail(): void
    {
        $row = factory(Model::class)->make(['shipping_id' => 1]);

        $this->auth()
            ->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testCreateTaxNotAllowedFail(): void
    {
        $row = factory(Model::class)->make(['tax_id' => 1]);

        $this->auth()
            ->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(404);
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
     * @return void
     */
    public function testCreateFirstSuccess(): void
    {
        $row = factory(Model::class)->make();

        $this->auth($this->userFirst())
            ->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure);
    }

    /**
     * @return void
     */
    public function testCreateSuccess(): void
    {
        $row = factory(Model::class)->make();

        $this->auth()
            ->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure);
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
            ->assertDontSee(' name ')
            ->assertDontSee(' tax number ')
            ->assertSee($this->t('validator.name-required'))
            ->assertSee($this->t('validator.tax_number-required'))
            ->assertSee($this->t('validator.type-required'));
    }

    /**
     * @return void
     */
    public function testUpdateIvalidFail(): void
    {
        $fail = [
            'email' => 'fail',
            'contact_email' => 'fail',
            'discount_id' => 'fail',
            'payment_id' => 'fail',
            'shipping_id' => 'fail',
            'tax_id' => 'fail',
            'type' => 'fail',
        ];

        $this->auth()
            ->json('PATCH', $this->route('update', $this->row()->id), $fail)
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' email ')
            ->assertDontSee(' contact email ')
            ->assertDontSee(' discount id ')
            ->assertDontSee(' payment id ')
            ->assertDontSee(' shipping id ')
            ->assertDontSee(' tax id ')
            ->assertSee($this->t('validator.email-email'))
            ->assertSee($this->t('validator.contact_email-email'))
            ->assertSee($this->t('validator.discount_id-integer'))
            ->assertSee($this->t('validator.payment_id-integer'))
            ->assertSee($this->t('validator.shipping_id-integer'))
            ->assertSee($this->t('validator.tax_id-integer'))
            ->assertSee($this->t('validator.type-in'));
    }

    /**
     * @return void
     */
    public function testUpdateDiscountNotAllowedFail(): void
    {
        $row = $this->row();
        $row->discount_id = 1;

        $this->auth()
            ->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testUpdatePaymentNotAllowedFail(): void
    {
        $row = $this->row();
        $row->payment_id = 1;

        $this->auth()
            ->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testUpdateShippingNotAllowedFail(): void
    {
        $row = $this->row();
        $row->shipping_id = 1;

        $this->auth()
            ->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testUpdateTaxNotAllowedFail(): void
    {
        $row = $this->row();
        $row->tax_id = 1;

        $this->auth()
            ->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testUpdateNoAuthFail(): void
    {
        $row = $this->row();

        $this->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testUpdateNotAllowedFail(): void
    {
        $row = $this->row();

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
        $user = $this->user();

        $row->discount_id = Models\Discount::where('user_id', $user->id)->first()->id;
        $row->payment_id = Models\Payment::where('user_id', $user->id)->first()->id;
        $row->shipping_id = Models\Shipping::where('user_id', $user->id)->first()->id;
        $row->tax_id = Models\Tax::where('user_id', $user->id)->first()->id;

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
     * @return \App\Models\Client
     */
    protected function row(): Model
    {
        return Model::orderBy('id', 'DESC')->first();
    }
}
