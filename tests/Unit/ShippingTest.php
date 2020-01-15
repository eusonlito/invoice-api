<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Shipping as Model;

class ShippingTest extends TestAbstract
{
    /**
     * @var string
     */
    protected string $route = 'shipping';

    /**
     * @var int
     */
    protected int $count = 0;

    /**
     * @var array
     */
    protected array $structure = [
        'id', 'name', 'subtotal', 'tax_percent', 'tax_amount', 'value',
        'description', 'default', 'enabled'
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
        $this->auth()
            ->json('GET', $this->route('enabled'))
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
     * 'name' => 'required|string',
     * 'subtotal' => 'numeric',
     * 'tax_percent' => 'numeric',
     * 'description' => 'string',
     * 'default' => 'boolean',
     * 'enabled' => 'boolean',
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
            ->assertSee($this->t('validator.name-required'));
    }

    /**
     * @return void
     */
    public function testCreateInvalidFail(): void
    {
        $fail = [
            'subtotal' => 'fail',
            'tax_percent' => 'fail'
        ];

        $this->auth()
            ->json('POST', $this->route('create'), $fail)
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' subtotal ')
            ->assertDontSee(' tax_percent ')
            ->assertSee($this->t('validator.subtotal-numeric'))
            ->assertSee($this->t('validator.tax_percent-numeric'));
    }

    /**
     * @return void
     */
    public function testCreateFirstSuccess(): void
    {
        $row = factory(Model::class)->make();
        $row->name = 'Test 10';
        $row->subtotal = 10;
        $row->tax_percent = 21;
        $row->description = 'Test Description';
        $row->default = true;

        $this->auth($this->userFirst())
            ->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure)
            ->assertJson(['subtotal' => 10])
            ->assertJson(['tax_percent' => 21.0])
            ->assertJson(['tax_amount' => 2.10])
            ->assertJson(['value' => 12.10]);
    }

    /**
     * @return void
     */
    public function testCreateSuccess(): void
    {
        $row = factory(Model::class)->make();
        $row->name = 'Test 10';
        $row->subtotal = 10;
        $row->tax_percent = 21;
        $row->description = 'Test Description';
        $row->default = true;

        $this->auth()
            ->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure)
            ->assertJson(['subtotal' => 10])
            ->assertJson(['tax_percent' => 21.0])
            ->assertJson(['tax_amount' => 2.10])
            ->assertJson(['value' => 12.10]);
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
    public function testUpdatEmptyFail(): void
    {
        $this->auth()
            ->json('PATCH', $this->route('update', $this->row()->id))
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' name ')
            ->assertSee($this->t('validator.name-required'));
    }

    /**
     * @return void
     */
    public function testUpdateInvalidFail(): void
    {
        $fail = [
            'subtotal' => 'fail',
            'tax_percent' => 'fail'
        ];

        $this->auth()
            ->json('PATCH', $this->route('update', $this->row()->id), $fail)
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.')
            ->assertDontSee(' subtotal ')
            ->assertDontSee(' tax_percent ')
            ->assertSee($this->t('validator.subtotal-numeric'))
            ->assertSee($this->t('validator.tax_percent-numeric'));
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
        $row->name = 'Test 8';
        $row->subtotal = 20;
        $row->tax_percent = 10;

        $this->auth()
            ->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure)
            ->assertJson(['subtotal' => 20])
            ->assertJson(['tax_percent' => 10])
            ->assertJson(['tax_amount' => 2])
            ->assertJson(['value' => 22]);
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
    public function testEnabledAfterSuccess(): void
    {
        $this->auth()
            ->json('GET', $this->route('enabled'))
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
     * @return \App\Models\Shipping
     */
    protected function row(): Model
    {
        return Model::orderBy('id', 'DESC')->first();
    }
}
