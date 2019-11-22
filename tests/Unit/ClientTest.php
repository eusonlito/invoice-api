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
        $this->auth()->json('GET', $this->route('index'))
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
        $this->auth()->json('GET', $this->route('export'))
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
     * 'tax_number' => 'required|string',
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
    public function testCreateFail(): void
    {
        $row = factory(Model::class)->make();

        $row->name = '';
        $row->phone = '';
        $row->email = '';
        $row->web = '';
        $row->tax_number = '';

        $row->contact_name = '';
        $row->contact_surname = '';
        $row->contact_phone = '';
        $row->contact_email = '';

        $this->auth()->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.');
    }

    /**
     * @return void
     */
    public function testCreateNameFail(): void
    {
        $row = factory(Model::class)->make(['name' => '']);

        $this->auth()->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('nombre');
    }

    /**
     * @return void
     */
    public function testCreateEmailFail(): void
    {
        $row = factory(Model::class)->make(['email' => 'fail']);

        $this->auth()->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('correo electr\u00f3nico');
    }

    /**
     * @return void
     */
    public function testCreateTaxNumberFail(): void
    {
        $row = factory(Model::class)->make(['tax_number' => '']);

        $this->auth()->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('NIF');
    }

    /**
     * @return void
     */
    public function testCreateContactEmailFail(): void
    {
        $row = factory(Model::class)->make(['contact_email' => 'fail']);

        $this->auth()->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('correo electr\u00f3nico de contacto');
    }

    /**
     * @return void
     */
    public function testCreateDiscountInvalidFail(): void
    {
        $row = factory(Model::class)->make(['discount_id' => 'fail']);

        $this->auth()->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('descuento');
    }

    /**
     * @return void
     */
    public function testCreateDiscountNotAllowedFail(): void
    {
        $row = factory(Model::class)->make(['discount_id' => 1]);

        $this->auth()->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testCreatePaymentInvalidFail(): void
    {
        $row = factory(Model::class)->make(['payment_id' => 'fail']);

        $this->auth()->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('pago');
    }

    /**
     * @return void
     */
    public function testCreatePaymentNotAllowedFail(): void
    {
        $row = factory(Model::class)->make(['payment_id' => 1]);

        $this->auth()->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testCreateShippingInvalidFail(): void
    {
        $row = factory(Model::class)->make(['shipping_id' => 'fail']);

        $this->auth()->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('env\u00edo');
    }

    /**
     * @return void
     */
    public function testCreateShippingNotAllowedFail(): void
    {
        $row = factory(Model::class)->make(['shipping_id' => 1]);

        $this->auth()->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testCreateTaxInvalidFail(): void
    {
        $row = factory(Model::class)->make(['tax_id' => 'fail']);

        $this->auth()->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('impuesto');
    }

    /**
     * @return void
     */
    public function testCreateTaxNotAllowedFail(): void
    {
        $row = factory(Model::class)->make(['tax_id' => 1]);

        $this->auth()->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testCreateNoAuthFail(): void
    {
        $row = factory(Model::class)->make();

        $this->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testCreateFirstSuccess(): void
    {
        $row = factory(Model::class)->make();
        $user = $this->userFirst();

        $this->auth($user)->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
    }

    /**
     * @return void
     */
    public function testCreateSuccess(): void
    {
        $row = factory(Model::class)->make();
        $user = $this->user();

        $this->auth()->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
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
        $this->auth($this->userFirst())->json('GET', $this->route('detail', $this->row()->id))
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testDetailSuccess(): void
    {
        $this->auth()->json('GET', $this->route('detail', $this->row()->id))
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
    }

    /**
     * @return void
     */
    public function testUpdateFail(): void
    {
        $row = $this->row();

        $row->name = '';
        $row->phone = '';
        $row->email = '';
        $row->web = '';
        $row->tax_number = '';

        $row->contact_name = '';
        $row->contact_surname = '';
        $row->contact_phone = '';
        $row->contact_email = '';

        $this->auth()->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.');
    }

    /**
     * @return void
     */
    public function testUpdateNameFail(): void
    {
        $row = $this->row();
        $row->name = '';

        $this->auth()->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('nombre');
    }

    /**
     * @return void
     */
    public function testUpdateEmailFail(): void
    {
        $row = $this->row();
        $row->email = 'fail';

        $this->auth()->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('correo electr\u00f3nico');
    }

    /**
     * @return void
     */
    public function testUpdateTaxNumberFail(): void
    {
        $row = $this->row();
        $row->tax_number = '';

        $this->auth()->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('NIF');
    }

    /**
     * @return void
     */
    public function testUpdateContactEmailFail(): void
    {
        $row = $this->row();
        $row->contact_email = 'fail';

        $this->auth()->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('correo electr\u00f3nico de contacto');
    }

    /**
     * @return void
     */
    public function testUpdateDiscountInvalidFail(): void
    {
        $row = $this->row();
        $row->discount_id = 'fail';

        $this->auth()->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('descuento');
    }

    /**
     * @return void
     */
    public function testUpdateDiscountNotAllowedFail(): void
    {
        $row = $this->row();
        $row->discount_id = 1;

        $this->auth()->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testUpdatePaymentInvalidFail(): void
    {
        $row = $this->row();
        $row->payment_id = 'fail';

        $this->auth()->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('pago');
    }

    /**
     * @return void
     */
    public function testUpdatePaymentNotAllowedFail(): void
    {
        $row = $this->row();
        $row->payment_id = 1;

        $this->auth()->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testUpdateShippingInvalidFail(): void
    {
        $row = $this->row();
        $row->shipping_id = 'fail';

        $this->auth()->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('env\u00edo');
    }

    /**
     * @return void
     */
    public function testUpdateShippingNotAllowedFail(): void
    {
        $row = $this->row();
        $row->shipping_id = 1;

        $this->auth()->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testUpdateTaxInvalidFail(): void
    {
        $row = $this->row();
        $row->tax_id = 'fail';

        $this->auth()->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('impuesto');
    }

    /**
     * @return void
     */
    public function testUpdateTaxNotAllowedFail(): void
    {
        $row = $this->row();
        $row->tax_id = 1;

        $this->auth()->json('PATCH', $this->route('update', $row->id), $row->toArray())
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

        $this->auth($this->userFirst())->json('PATCH', $this->route('update', $row->id), $row->toArray())
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

        $this->auth()->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
    }

    /**
     * @return void
     */
    public function testIndexAfterSuccess(): void
    {
        $this->auth()->json('GET', $this->route('index'))
            ->assertStatus(200)
            ->assertJsonCount($this->count + 1);
    }

    /**
     * @return void
     */
    public function testExportAfterSuccess(): void
    {
        $this->auth()->json('GET', $this->route('export'))
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

    /**
     * @return array
     */
    protected function structure(): array
    {
        return [
            'id', 'name', 'phone', 'email', 'contact_name', 'contact_surname',
            'created_at', 'web', 'tax_number', 'contact_phone', 'contact_email',
            'comment', 'discount', 'payment', 'shipping', 'tax'
        ];
    }
}
