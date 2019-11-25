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
     * @var array
     */
    protected array $structure = [
        'id' , 'name', 'address', 'city', 'state', 'postal_code', 'country', 'phone',
        'email', 'comment', 'tax_number', 'billing', 'shipping', 'enabled',
        'client' => ['id']
    ];

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
    public function testCreateEmptyFail(): void
    {
        $this->auth()->json('POST', $this->route('create', $this->client()->id))
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' name ')
            ->assertDontSee(' address ')
            ->assertDontSee(' city ')
            ->assertDontSee(' state ')
            ->assertDontSee(' postal code ')
            ->assertDontSee(' country ')
            ->assertSee($this->t('validator.name-required'))
            ->assertSee($this->t('validator.address-required'))
            ->assertSee($this->t('validator.city-required'))
            ->assertSee($this->t('validator.state-required'))
            ->assertSee($this->t('validator.postal_code-required'))
            ->assertSee($this->t('validator.country-required'));
    }

    /**
     * @return void
     */
    public function testCreateInvalidFail(): void
    {
        $fail = ['email' => 'fail'];

        $this->auth()->json('POST', $this->route('create', $this->client()->id), $fail)
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' email ')
            ->assertSee($this->t('validator.email-email'));
    }

    /**
     * @return void
     */
    public function testCreateBillingNifFail(): void
    {
        $fail = [
            'billing' => true,
            'tax_number' => ''
        ];

        $this->auth()->json('POST', $this->route('create', $this->client()->id), $fail)
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' billing ')
            ->assertDontSee(' tax number ')
            ->assertSee($this->t('validator.tax_number-required'));
    }

    /**
     * @return void
     */
    public function testCreateNoAuthFail(): void
    {
        $this->json('POST', $this->route('create', $this->client()->id))
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
            ->assertJsonStructure($this->structure);
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
            ->assertJsonStructure($this->structure);
    }

    /**
     * @return void
     */
    public function testUpdateRequiredFail(): void
    {
        $row = $this->row();

        $this->auth()->json('PATCH', $this->route('update', $row->id))
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' name ')
            ->assertDontSee(' address ')
            ->assertDontSee(' city ')
            ->assertDontSee(' state ')
            ->assertDontSee(' postal code ')
            ->assertDontSee(' country ')
            ->assertSee($this->t('validator.name-required'))
            ->assertSee($this->t('validator.address-required'))
            ->assertSee($this->t('validator.city-required'))
            ->assertSee($this->t('validator.state-required'))
            ->assertSee($this->t('validator.postal_code-required'))
            ->assertSee($this->t('validator.country-required'));
    }

    /**
     * @return void
     */
    public function testUpdateEmailFail(): void
    {
        $row = $this->row();
        $fail = ['email' => 'fail'];

        $this->auth()->json('PATCH', $this->route('update', $row->id), $fail)
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' email ')
            ->assertSee($this->t('validator.email-email'));
    }

    /**
     * @return void
     */
    public function testUpdateBillingNifFail(): void
    {
        $row = $this->row();
        $fail = [
            'billing' => true,
            'tax_number' => ''
        ];

        $this->auth()->json('PATCH', $this->route('update', $row->id), $fail)
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' billing ')
            ->assertDontSee(' tax number ')
            ->assertSee($this->t('validator.tax_number-required'));
    }

    /**
     * @return void
     */
    public function testUpdateNoAuthFail(): void
    {
        $row = $this->row();

        $this->json('PATCH', $this->route('update', $row->id))
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
            ->assertJsonStructure($this->structure);
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
}
