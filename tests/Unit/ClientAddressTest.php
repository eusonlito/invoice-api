<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Models;
use App\Models\ClientAddress as Model;

class ClientAddressTest extends TestAbstract
{
    /**
     * @var string
     */
    protected string $route = 'client-address';

    /**
     * @var int
     */
    protected int $count = 0;

    /**
     * @return void
     */
    public function testEnabledNoAuthFail(): void
    {
        $this->json('GET', $this->route('enabled'))
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testEnabledSuccess(): void
    {
        $this->auth()->json('GET', $this->route('enabled'))
            ->assertStatus(200)
            ->assertJsonCount($this->count);
    }

    /**
     * @return void
     */
    public function testClientNoAuthFail(): void
    {
        $this->json('GET', $this->route('client', $this->client()->id))
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testClientSuccess(): void
    {
        $this->auth()->json('GET', $this->route('client', $this->client()->id))
            ->assertStatus(200)
            ->assertJsonCount($this->count);
    }

    /**
     * @return void
     */
    public function testClienEnabledtNoAuthFail(): void
    {
        $this->json('GET', $this->route('client.enabled', $this->client()->id))
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testClientEnabledSuccess(): void
    {
        $this->auth()->json('GET', $this->route('client.enabled', $this->client()->id))
            ->assertStatus(200)
            ->assertJsonCount($this->count);
    }

    /**
     * Rules:
     *
     * 'name' => 'required',
     * 'address' => 'required',
     * 'city' => 'required',
     * 'state' => 'required',
     * 'postal_code' => 'required',
     * 'country' => 'required',
     * 'phone' => 'string',
     * 'email' => 'email|string',
     * 'comment' => 'string',
     * 'tax_number' => 'required_if:billing,true|string',
     * 'billing' => 'boolean',
     * 'shipping' => 'boolean',
     * 'enabled' => 'boolean',
     */

    /**
     * @return void
     */
    public function testCreateFail(): void
    {
        $row = factory(Model::class)->make();

        $row->name = '';
        $row->address = '';
        $row->city = '';
        $row->state = '';
        $row->postal_code = '';
        $row->country = '';
        $row->phone = '';
        $row->email = '';
        $row->comment = '';
        $row->tax_number = '';
        $row->billing = '';
        $row->shipping = '';
        $row->enabled = '';

        $this->auth()->json('POST', $this->route('create', $this->client()->id), $row->toArray())
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.');
    }

    /**
     * @return void
     */
    public function testCreateRequiredFail(): void
    {
        $row = factory(Model::class)->make();

        $row->name = '';
        $row->address = '';
        $row->city = '';
        $row->state = '';
        $row->postal_code = '';
        $row->country = '';
        $row->tax_number = '';

        $this->auth()->json('POST', $this->route('create', $this->client()->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('nombre')
            ->assertSee('direcci\u00f3n')
            ->assertSee('ciudad')
            ->assertSee('provincia')
            ->assertSee('c\u00f3digo postal')
            ->assertSee('pa\u00eds')
            ->assertSee('NIF');
    }

    /**
     * @return void
     */
    public function testCreateEmailFail(): void
    {
        $row = factory(Model::class)->make();
        $row->email = 'fail';

        $this->auth()->json('POST', $this->route('create', $this->client()->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('correo electr\u00f3nico');
    }

    /**
     * @return void
     */
    public function testCreateTaxNumberFail(): void
    {
        $row = factory(Model::class)->make(['tax_number' => '']);

        $this->auth()->json('POST', $this->route('create', $this->client()->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('NIF');
    }

    /**
     * @return void
     */
    public function testCreateNoAuthFail(): void
    {
        $row = factory(Model::class)->make();

        $this->json('POST', $this->route('create', $this->client()->id), $row->toArray())
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testCreateFirstSuccess(): void
    {
        $row = factory(Model::class)->make();
        $user = $this->userFirst();

        $this->auth($user)->json('POST', $this->route('create', $this->clientByUser($user)->id), $row->toArray())
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

        $this->auth()->json('POST', $this->route('create', $this->client()->id), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
    }

    /**
     * @return void
     */
    public function testUpdateRequiredFail(): void
    {
        $row = $this->row();

        $row->name = '';
        $row->address = '';
        $row->city = '';
        $row->state = '';
        $row->postal_code = '';
        $row->country = '';
        $row->tax_number = '';

        $this->auth()->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('nombre')
            ->assertSee('direcci\u00f3n')
            ->assertSee('ciudad')
            ->assertSee('provincia')
            ->assertSee('c\u00f3digo postal')
            ->assertSee('pa\u00eds')
            ->assertSee('NIF');
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

        $this->auth()->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
    }

    /**
     * @return void
     */
    public function testEnabledAfterSuccess(): void
    {
        $this->auth()->json('GET', $this->route('enabled'))
            ->assertStatus(200)
            ->assertJsonCount($this->count + 1);
    }

    /**
     * @return void
     */
    public function testClientAfterSuccess(): void
    {
        $this->auth()->json('GET', $this->route('client', $this->client()->id))
            ->assertStatus(200)
            ->assertJsonCount($this->count + 1);
    }

    /**
     * @return void
     */
    public function testClientEnabledAfterSuccess(): void
    {
        $this->auth()->json('GET', $this->route('client.enabled', $this->client()->id))
            ->assertStatus(200)
            ->assertJsonCount($this->count + 1);
    }

    /**
     * @return \App\Models\Client
     */
    protected function client(): Models\Client
    {
        return $this->clientByUser($this->user());
    }

    /**
     * @param \App\Models\User $user
     *
     * @return \App\Models\Client
     */
    protected function clientByUser(Models\User $user): Models\Client
    {
        return Models\Client::where('user_id', $user->id)->orderBy('id', 'DESC')->first();
    }

    /**
     * @return \App\Models\ClientAddress
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
            'id' , 'name', 'address', 'city', 'state', 'postal_code', 'country', 'phone',
            'email', 'comment', 'tax_number', 'billing', 'shipping', 'enabled',
            'client' => ['id']
        ];
    }
}
