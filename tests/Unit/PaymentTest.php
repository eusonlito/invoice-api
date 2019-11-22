<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Payment as Model;

class PaymentTest extends TestAbstract
{
    /**
     * @return void
     */
    public function testIndexNoAuthFail(): void
    {
        $this->json('GET', route('payment.index'))->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testIndexSuccess(): void
    {
        $this->auth()->json('GET', route('payment.index'))
            ->assertStatus(200)
            ->assertJsonCount(1);
    }

    /**
     * @return void
     */
    public function testExportNoAuthFail(): void
    {
        $this->json('GET', route('payment.export'))->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testExportSuccess(): void
    {
        $this->auth()->json('GET', route('payment.export'))
            ->assertStatus(200)
            ->assertJsonCount(1);
    }

    /**
     * Rules:
     *
     * 'name' => 'required|string',
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

        $this->auth()->json('POST', route('payment.create'), $row->toArray())
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

        $this->auth()->json('POST', route('payment.create'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('nombre');
    }

    /**
     * @return void
     */
    public function testCreateNoAuthFail(): void
    {
        $row = factory(Model::class)->make();

        $this->json('POST', route('payment.create'), $row->toArray())
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testCreateSuccess(): void
    {
        $row = factory(Model::class)->make();
        $row->name = 'Test';
        $row->description = 'Test Description';
        $row->default = true;
        $row->enabled = true;

        $this->auth()->json('POST', route('payment.create'), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
    }

    /**
     * @return void
     */
    public function testDetailFail(): void
    {
        $this->auth($this->userFirst())->json('GET', route('payment.detail', $this->row()->id))
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testDetailSuccess(): void
    {
        $this->auth()->json('GET', route('payment.detail', $this->row()->id))
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
    }

    /**
     * @return void
     */
    public function testUpdateFail(): void
    {
        $this->auth()->json('PATCH', route('payment.update', $this->row()->id))
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

        $this->auth()->json('PATCH', route('payment.update', $row->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('nombre');
    }

    /**
     * @return void
     */
    public function testUpdateNoAuthFail(): void
    {
        $row = $this->row();

        $this->json('PATCH', route('payment.update', $row->id), $row->toArray())
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testUpdateNotAllowedFail(): void
    {
        $row = $this->row();

        $this->auth($this->userFirst())
            ->json('PATCH', route('payment.update', $row->id), $row->toArray())
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testUpdateSuccess(): void
    {
        $row = $this->row();

        $this->auth()->json('PATCH', route('payment.update', $row->id), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
    }

    /**
     * @return \App\Models\Payment
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
        return ['id', 'name', 'description', 'default', 'enabled'];
    }
}
