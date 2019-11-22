<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Discount as Model;

class DiscountTest extends TestAbstract
{
    /**
     * @return void
     */
    public function testIndexNoAuthFail(): void
    {
        $this->json('GET', route('discount.index'))->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testIndexSuccess(): void
    {
        $this->auth()->json('GET', route('discount.index'))
            ->assertStatus(200)
            ->assertJsonCount(2);
    }

    /**
     * @return void
     */
    public function testExportNoAuthFail(): void
    {
        $this->json('GET', route('discount.export'))->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testExportSuccess(): void
    {
        $this->auth()->json('GET', route('discount.export'))
            ->assertStatus(200)
            ->assertJsonCount(2);
    }

    /**
     * Rules:
     *
     * 'name' => 'required|string',
     * 'type' => 'required|in:fixed,percent',
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

        $this->auth()->json('POST', route('discount.create'), $row->toArray())
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

        $this->auth()->json('POST', route('discount.create'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('nombre');
    }

    /**
     * @return void
     */
    public function testCreateTypeEmptyFail(): void
    {
        $row = factory(Model::class)->make(['type' => '']);

        $this->auth()->json('POST', route('discount.create'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('tipo');
    }

    /**
     * @return void
     */
    public function testCreateTypeInvalidFail(): void
    {
        $row = factory(Model::class)->make(['type' => 'fail']);

        $this->auth()->json('POST', route('discount.create'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('tipo');
    }

    /**
     * @return void
     */
    public function testCreateValueFail(): void
    {
        $row = factory(Model::class)->make();

        $this->auth()->json('POST', route('discount.create'), ['value' => 'f'] + $row->toArray())
            ->assertStatus(422)
            ->assertSee('valor');
    }

    /**
     * @return void
     */
    public function testCreateNoAuthFail(): void
    {
        $row = factory(Model::class)->make();

        $this->json('POST', route('discount.create'), $row->toArray())
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testCreateSuccess(): void
    {
        $row = factory(Model::class)->make();
        $row->name = 'Test';
        $row->type = 'percent';
        $row->value = 10;
        $row->description = 'Test Description';
        $row->default = true;
        $row->enabled = true;

        $this->auth()->json('POST', route('discount.create'), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
    }

    /**
     * @return void
     */
    public function testDetailFail(): void
    {
        $this->auth($this->userFirst())->json('GET', route('discount.detail', $this->row()->id))
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testDetailSuccess(): void
    {
        $this->auth()->json('GET', route('discount.detail', $this->row()->id))
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
    }

    /**
     * @return void
     */
    public function testUpdateFail(): void
    {
        $this->auth()->json('PATCH', route('discount.update', $this->row()->id))
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

        $this->auth()->json('PATCH', route('discount.update', $row->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('nombre');
    }

    /**
     * @return void
     */
    public function testUpdateTypeEmptyFail(): void
    {
        $row = $this->row();
        $row->type = '';

        $this->auth()->json('PATCH', route('discount.update', $row->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('tipo');
    }

    /**
     * @return void
     */
    public function testUpdateTypeInvalidFail(): void
    {
        $row = $this->row();
        $row->type = 'fail';

        $this->auth()->json('PATCH', route('discount.update', $row->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('tipo');
    }

    /**
     * @return void
     */
    public function testUpdateValueFail(): void
    {
        $row = $this->row();

        $this->auth()->json('PATCH', route('discount.update', $row->id), ['value' => 'f'] + $row->toArray())
            ->assertStatus(422)
            ->assertSee('valor');
    }

    /**
     * @return void
     */
    public function testUpdateNoAuthFail(): void
    {
        $row = $this->row();

        $this->json('PATCH', route('discount.update', $row->id), $row->toArray())
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testUpdateNotAllowedFail(): void
    {
        $row = $this->row();

        $this->auth($this->userFirst())
            ->json('PATCH', route('discount.update', $row->id), $row->toArray())
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testUpdateSuccess(): void
    {
        $row = $this->row();

        $this->auth()->json('PATCH', route('discount.update', $row->id), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
    }

    /**
     * @return \App\Models\Discount
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
        return ['id', 'name', 'type', 'value', 'description', 'default', 'enabled'];
    }
}
