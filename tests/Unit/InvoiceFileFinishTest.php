<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Models;
use App\Models\InvoiceFile as Model;

class InvoiceFileFinishTest extends TestAbstract
{
    /**
     * @var string
     */
    protected string $route = 'invoice-file';

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
    public function testInvoiceSuccess(): void
    {
        $this->auth()->json('GET', $this->route('invoice', $this->invoice()->id))
            ->assertStatus(200)
            ->assertJsonCount($this->count);
    }

    /**
     * @return \App\Models\InvoiceFile
     */
    protected function row(): Model
    {
        return Model::orderBy('id', 'DESC')->first();
    }

    /**
     * @return \App\Models\Invoice
     */
    protected function invoice(): Models\Invoice
    {
        return Models\Invoice::orderBy('id', 'DESC')->first();
    }
}
