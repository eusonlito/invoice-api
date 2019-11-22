<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Discount as Model;

class DiscountTest extends TestAbstract
{
    /**
     * @var string
     */
    protected string $route = 'discount';

    /**
     * @var int
     */
    protected int $count = 2;

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
    public function testCreateTypeEmptyFail(): void
    {
        $row = factory(Model::class)->make(['type' => '']);

        $this->auth()->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('tipo');
    }

    /**
     * @return void
     */
    public function testCreateTypeInvalidFail(): void
    {
        $row = factory(Model::class)->make(['type' => 'fail']);

        $this->auth()->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('tipo');
    }

    /**
     * @return void
     */
    public function testCreateValueFail(): void
    {
        $row = factory(Model::class)->make();

        $this->auth()->json('POST', $this->route('create'), ['value' => 'f'] + $row->toArray())
            ->assertStatus(422)
            ->assertSee('valor');
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
        $row->name = 'Test';
        $row->type = 'percent';
        $row->value = 10;
        $row->description = 'Test Description';
        $row->default = true;
        $row->enabled = true;

        $this->auth($this->userFirst())->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
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
        $this->auth()->json('PATCH', $this->route('update', $this->row()->id))
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
    public function testUpdateTypeEmptyFail(): void
    {
        $row = $this->row();
        $row->type = '';

        $this->auth()->json('PATCH', $this->route('update', $row->id), $row->toArray())
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

        $this->auth()->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('tipo');
    }

    /**
     * @return void
     */
    public function testUpdateValueFail(): void
    {
        $row = $this->row();

        $this->auth()->json('PATCH', $this->route('update', $row->id), ['value' => 'f'] + $row->toArray())
            ->assertStatus(422)
            ->assertSee('valor');
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
    public function testEnabledAfterSuccess(): void
    {
        $this->auth()->json('GET', $this->route('enabled'))
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
