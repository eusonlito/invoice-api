<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Models\InvoiceStatus as Model;

class InvoiceStatusTest extends TestAbstract
{
    /**
     * @return void
     */
    public function testIndexNoAuthFail(): void
    {
        $this->json('GET', route('invoice-status.index'))->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testIndexSuccess(): void
    {
        $this->auth()->json('GET', route('invoice-status.index'))
            ->assertStatus(200)
            ->assertJsonCount(4);
    }

    /**
     * @return void
     */
    public function testExportNoAuthFail(): void
    {
        $this->json('GET', route('invoice-status.export'))->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testExportSuccess(): void
    {
        $this->auth()->json('GET', route('invoice-status.export'))
            ->assertStatus(200)
            ->assertJsonCount(4);
    }

    /**
     * Rules:
     *
     * 'name' => 'required|string',
     * 'order' => 'required|integer',
     * 'paid' => 'boolean',
     * 'default' => 'boolean',
     * 'enabled' => 'boolean',
     */

    /**
     * @return void
     */
    public function testCreateFail(): void
    {
        $row = factory(Model::class)->make();

        $this->auth()->json('POST', route('invoice-status.create'), $row->toArray())
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

        $this->auth()->json('POST', route('invoice-status.create'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('nombre');
    }

    /**
     * @return void
     */
    public function testCreateOrderEmptyFail(): void
    {
        $row = factory(Model::class)->make(['order' => null]);

        $this->auth()->json('POST', route('invoice-status.create'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('orden');
    }

    /**
     * @return void
     */
    public function testCreateOrderInvalidFail(): void
    {
        $row = factory(Model::class)->make();

        $this->auth()->json('POST', route('invoice-status.create'), ['order' => 'f'] + $row->toArray())
            ->assertStatus(422)
            ->assertSee('orden');
    }

    /**
     * @return void
     */
    public function testCreateNoAuthFail(): void
    {
        $row = factory(Model::class)->make();

        $this->json('POST', route('invoice-status.create'), $row->toArray())
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testCreateSuccess(): void
    {
        $row = factory(Model::class)->make();
        $row->name = 'Test';
        $row->order = 1;
        $row->paid = true;
        $row->default = true;

        $this->auth()->json('POST', route('invoice-status.create'), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
    }

    /**
     * @return void
     */
    public function testDetailFail(): void
    {
        $this->auth($this->userFirst())->json('GET', route('invoice-status.detail', $this->row()->id))
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testDetailSuccess(): void
    {
        $this->auth()->json('GET', route('invoice-status.detail', $this->row()->id))
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
    }

    /**
     * @return void
     */
    public function testUpdateFail(): void
    {
        $this->auth()->json('PATCH', route('invoice-status.update', $this->row()->id))
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

        $this->auth()->json('PATCH', route('invoice-status.update', $row->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('nombre');
    }

    /**
     * @return void
     */
    public function testUpdateOrderEmptyFail(): void
    {
        $row = $this->row();
        $row->order = null;

        $this->auth()->json('PATCH', route('invoice-status.update', $row->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('orden');
    }

    /**
     * @return void
     */
    public function testUpdateOrderInvalidFail(): void
    {
        $row = $this->row();

        $this->auth()->json('PATCH', route('invoice-status.update', $row->id), ['order' => 'f'] + $row->toArray())
            ->assertStatus(422)
            ->assertSee('orden');
    }

    /**
     * @return void
     */
    public function testUpdateNoAuthFail(): void
    {
        $row = $this->row();

        $this->json('PATCH', route('invoice-status.update', $row->id), $row->toArray())
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testUpdateNotAllowedFail(): void
    {
        $row = $this->row();

        $this->auth($this->userFirst())
            ->json('PATCH', route('invoice-status.update', $row->id), $row->toArray())
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testUpdateSuccess(): void
    {
        $row = $this->row();

        $this->auth()->json('PATCH', route('invoice-status.update', $row->id), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
    }

    /**
     * @return \App\Models\InvoiceStatus
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
        return ['id', 'name', 'order', 'paid', 'default', 'enabled'];
    }
}
