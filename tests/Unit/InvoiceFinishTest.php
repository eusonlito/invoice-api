<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Invoice as Model;

class InvoiceFinishTest extends TestAbstract
{
    /**
     * @var string
     */
    protected string $route = 'invoice';

    /**
     * @var int
     */
    protected int $count = 0;

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
        $this->auth($this->userFirst())
            ->json('DELETE', $this->route('delete', $this->row()->id))
            ->assertStatus(404);
    }

    /**
     * @return void
     */
    public function testSuccess(): void
    {
        $this->auth()
            ->json('DELETE', $this->route('delete', $this->row()->id))
            ->assertStatus(200);
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
    public function testExportSuccess(): void
    {
        $this->auth()
            ->json('GET', $this->route('export'))
            ->assertStatus(200)
            ->assertJsonCount($this->count);
    }

    /**
     * @return \App\Models\Invoice
     */
    protected function row(): Model
    {
        return Model::orderBy('id', 'DESC')->first();
    }
}
