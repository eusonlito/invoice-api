<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Models\InvoiceSerie as Model;

class InvoiceSerieTest extends TestAbstract
{
    /**
     * @var string
     */
    protected string $route = 'invoice-serie';

    /**
     * @var int
     */
    protected int $count = 4;

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
     * 'name' => 'required',
     * 'number_prefix' => 'required',
     * 'number_fill' => 'integer',
     * 'number_next' => 'integer',
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
    public function testCreatePrefixFail(): void
    {
        $row = factory(Model::class)->make(['number_prefix' => '']);

        $this->auth()->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('prefijo');
    }

    /**
     * @return void
     */
    public function testCreateFillFail(): void
    {
        $row = factory(Model::class)->make(['number_fill' => 'f']);

        $this->auth()->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('relleno');
    }

    /**
     * @return void
     */
    public function testCreateNextFail(): void
    {
        $row = factory(Model::class)->make(['number_next' => 'f']);

        $this->auth()->json('POST', $this->route('create'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('siguiente');
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
    public function testCreateSuccess(): void
    {
        $row = factory(Model::class)->make();
        $row->name = 'Test';
        $row->number_prefix = 'T';
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
    public function testUpdatePrefixFail(): void
    {
        $row = $this->row();
        $row->number_prefix = '';

        $this->auth()->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('prefijo');
    }

    /**
     * @return void
     */
    public function testUpdateFillFail(): void
    {
        $row = $this->row();
        $row->number_fill = 'f';

        $this->auth()->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('relleno');
    }

    /**
     * @return void
     */
    public function testUpdateNextFail(): void
    {
        $row = $this->row();
        $row->number_next = 'f';

        $this->auth()->json('PATCH', $this->route('update', $row->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('siguiente');
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
     * @return \App\Models\InvoiceSerie
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
        return ['id', 'name', 'number_prefix', 'number_fill', 'number_next', 'default', 'enabled'];
    }
}
