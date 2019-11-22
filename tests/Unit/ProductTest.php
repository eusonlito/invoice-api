<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Product as Model;

class ProductTest extends TestAbstract
{
    /**
     * @return void
     */
    public function testIndexNoAuthFail(): void
    {
        $this->json('GET', route('product.index'))->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testIndexSuccess(): void
    {
        $this->auth()->json('GET', route('product.index'))
            ->assertStatus(200)
            ->assertJsonCount(0);
    }

    /**
     * @return void
     */
    public function testExportNoAuthFail(): void
    {
        $this->json('GET', route('product.export'))->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testExportSuccess(): void
    {
        $this->auth()->json('GET', route('product.export'))
            ->assertStatus(200)
            ->assertJsonCount(0);
    }

    /**
     * Rules:
     *
     * 'reference' => 'string',
     * 'name' => 'required|string',
     * 'price' => 'required|numeric',
     * 'enabled' => 'boolean',
     */

    /**
     * @return void
     */
    public function testCreateFail(): void
    {
        $row = factory(Model::class)->make();

        $this->auth()->json('POST', route('product.create'), $row->toArray())
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.');
    }

    /**
     * @return void
     */
    public function testCreateReferenceFail(): void
    {
        $row = factory(Model::class)->make();

        $this->auth()->json('POST', route('product.create'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('referencia');
    }

    /**
     * @return void
     */
    public function testCreateNameFail(): void
    {
        $row = factory(Model::class)->make(['name' => '']);

        $this->auth()->json('POST', route('product.create'), $row->toArray())
            ->assertStatus(422)
            ->assertSee('nombre');
    }

    /**
     * @return void
     */
    public function testCreatePriceEmptyFail(): void
    {
        $row = factory(Model::class)->make();

        $this->auth()->json('POST', route('product.create'), ['price' => '' ] + $row->toArray())
            ->assertStatus(422)
            ->assertSee('precio');
    }

    /**
     * @return void
     */
    public function testCreatePriceInvalidFail(): void
    {
        $row = factory(Model::class)->make();

        $this->auth()->json('POST', route('product.create'), ['price' => 'f'] + $row->toArray())
            ->assertStatus(422)
            ->assertSee('precio');
    }

    /**
     * @return void
     */
    public function testCreateNoAuthFail(): void
    {
        $row = factory(Model::class)->make();

        $this->json('POST', route('product.create'), $row->toArray())
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testCreateSuccess(): void
    {
        $row = factory(Model::class)->make();
        $row->reference = 'TEST';
        $row->name = 'Test';
        $row->price = 10;
        $row->enabled = true;

        $this->auth()->json('POST', route('product.create'), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
    }

    /**
     * @return void
     */
    public function testDetailFail(): void
    {
        $this->auth($this->userFirst())->json('GET', route('product.detail', $this->row()->id))
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testDetailSuccess(): void
    {
        $this->auth()->json('GET', route('product.detail', $this->row()->id))
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
    }

    /**
     * @return void
     */
    public function testUpdateFail(): void
    {
        $this->auth()->json('PATCH', route('product.update', $this->row()->id))
            ->assertStatus(422)
            ->assertDontSee('validator.')
            ->assertDontSee('validation.');
    }

    /**
     * @return void
     */
    public function testUpdateReferenceFail(): void
    {
        $row = $this->row();

        $this->auth()->json('PATCH', route('product.update', $row->id), ['reference' => null] + $row->toArray())
            ->assertStatus(422)
            ->assertSee('referencia');
    }

    /**
     * @return void
     */
    public function testUpdateNameFail(): void
    {
        $row = $this->row();
        $row->name = '';

        $this->auth()->json('PATCH', route('product.update', $row->id), $row->toArray())
            ->assertStatus(422)
            ->assertSee('nombre');
    }

    /**
     * @return void
     */
    public function testUpdatePriceEmptyFail(): void
    {
        $row = $this->row();

        $this->auth()->json('PATCH', route('product.update', $row->id), ['price' => ''] + $row->toArray())
            ->assertStatus(422)
            ->assertSee('precio');
    }

    /**
     * @return void
     */
    public function testUpdatePriceInvalidFail(): void
    {
        $row = $this->row();

        $this->auth()->json('PATCH', route('product.update', $row->id), ['price' => 'f'] + $row->toArray())
            ->assertStatus(422)
            ->assertSee('precio');
    }

    /**
     * @return void
     */
    public function testUpdateNoAuthFail(): void
    {
        $row = $this->row();

        $this->json('PATCH', route('product.update', $row->id), $row->toArray())
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testUpdateNotAllowedFail(): void
    {
        $row = $this->row();

        $this->auth($this->userFirst())
            ->json('PATCH', route('product.update', $row->id), $row->toArray())
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testUpdateSuccess(): void
    {
        $row = $this->row();

        $this->auth()->json('PATCH', route('product.update', $row->id), $row->toArray())
            ->assertStatus(200)
            ->assertJsonStructure($this->structure());
    }

    /**
     * @return \App\Models\Product
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
        return ['id', 'reference', 'name', 'price', 'enabled'];
    }
}
