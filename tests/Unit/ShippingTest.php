<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Shipping as Model;

class ShippingTest extends TestAbstract
{
    /**
     * @return void
     */
    public function testIndexNoAuthFail(): void
    {
        $this->json('GET', route('shipping.index'))->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testIndexSuccess(): void
    {
        $this->auth()->json('GET', route('shipping.index'))
            ->assertStatus(200)
            ->assertJsonCount(0);
    }

    /**
     * @return void
     */
    public function testExportNoAuthFail(): void
    {
        $this->json('GET', route('shipping.export'))->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testExportSuccess(): void
    {
        $this->auth()->json('GET', route('shipping.export'))
            ->assertStatus(200)
            ->assertJsonCount(0);
    }

    /**
     * Rules:
     *
     * 'name' => 'required|string',
     * 'value' => 'numeric',
     * 'description' => 'string',
     * 'default' => 'boolean',
     * 'enabled' => 'boolean',
     */

    /**
     * @return void
     */
    public function testCreateFail(): void
    {
        $row = factory(Model::class)->make();

        $this->auth()->json('POST', route('shipping.create'), $row->toArray())
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

        $this->auth()->json('POST', route('shipping.create'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('nombre');
    }

    /**
     * @return void
     */
    public function testCreateValueFail(): void
    {
        $row = factory(Model::class)->make();

        $this->auth()->json('POST', route('shipping.create'), ['value' => 'f'] + $row->toArray())
            ->assertStatus(422)
            ->assertSee('valor');
    }

    /**
     * @return void
     */
    public function testCreateNoAuthFail(): void
    {
        $row = factory(Model::class)->make();

        $this->json('POST', route('shipping.create'), $row->toArray())
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testCreateSuccess(): void
    {
        $row = factory(Model::class)->make();
        $row->name = 'Test';
        $row->value = 10;
        $row->description = 'Test Description';
        $row->default = true;
        $row->enabled = true;

        $this->auth()->json('POST', route('shipping.create'), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
    }

    /**
     * @return void
     */
    public function testDetailFail(): void
    {
        $this->auth($this->userFirst())->json('GET', route('shipping.detail', $this->row()->id))
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testDetailSuccess(): void
    {
        $this->auth()->json('GET', route('shipping.detail', $this->row()->id))
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
    }

    /**
     * @return void
     */
    public function testUpdateFail(): void
    {
        $this->auth()->json('PATCH', route('shipping.update', $this->row()->id))
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

        $this->auth()->json('PATCH', route('shipping.update', $row->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('nombre');
    }

    /**
     * @return void
     */
    public function testUpdateValueFail(): void
    {
        $row = $this->row();

        $this->auth()->json('PATCH', route('shipping.update', $row->id), ['value' => 'f'] + $row->toArray())
            ->assertStatus(422)
            ->assertSee('valor');
    }

    /**
     * @return void
     */
    public function testUpdateNoAuthFail(): void
    {
        $row = $this->row();

        $this->json('PATCH', route('shipping.update', $row->id), $row->toArray())
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testUpdateNotAllowedFail(): void
    {
        $row = $this->row();

        $this->auth($this->userFirst())
            ->json('PATCH', route('shipping.update', $row->id), $row->toArray())
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testUpdateSuccess(): void
    {
        $row = $this->row();

        $this->auth()->json('PATCH', route('shipping.update', $row->id), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
    }

    /**
     * @return \App\Models\Shipping
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
        return ['id', 'name', 'value', 'description', 'default', 'enabled'];
    }
}
