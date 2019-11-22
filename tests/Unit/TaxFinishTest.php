<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Tax as Model;

class TaxFinishTest extends TestAbstract
{
    /**
     * @var string
     */
    protected string $route = 'tax';

    /**
     * @var int
     */
    protected int $count = 1;

    /**
     * @return void
     */
    public function testNoAuthFail(): void
    {
        $this->json('DELETE', $this->route('delete', $this->row()->id))
            ->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testNotAllowedFail(): void
    {
        $this->auth($this->userFirst())->json('DELETE', $this->route('delete', $this->row()->id))
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testSuccess(): void
    {
        $this->auth()->json('DELETE', $this->route('delete', $this->row()->id))
            ->assertStatus(200);
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
    public function testEnabledSuccess(): void
    {
        $this->auth()->json('GET', $this->route('index'))
            ->assertStatus(200)
            ->assertJsonCount($this->count);
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
     * @return \App\Models\Tax
     */
    protected function row(): Model
    {
        return Model::orderBy('id', 'DESC')->first();
    }
}
