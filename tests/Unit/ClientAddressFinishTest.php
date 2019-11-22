<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Models;
use App\Models\ClientAddress as Model;

class ClientAddressFinishTest extends TestAbstract
{
    /**
     * @var string
     */
    protected string $route = 'client-address';

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
    public function testEnabledSuccess(): void
    {
        $this->auth()->json('GET', $this->route('enabled'))
            ->assertStatus(200)
            ->assertJsonCount($this->count);
    }

    /**
     * @return void
     */
    public function testClientSuccess(): void
    {
        $this->auth()->json('GET', $this->route('client', $this->client()->id))
            ->assertStatus(200)
            ->assertJsonCount($this->count);
    }

    /**
     * @return void
     */
    public function testClientEnabledSuccess(): void
    {
        $this->auth()->json('GET', $this->route('client.enabled', $this->client()->id))
            ->assertStatus(200)
            ->assertJsonCount($this->count);
    }

    /**
     * @return \App\Models\ClientAddress
     */
    protected function row(): Model
    {
        return Model::orderBy('id', 'DESC')->first();
    }

    /**
     * @return \App\Models\Client
     */
    protected function client(): Models\Client
    {
        return Models\Client::orderBy('id', 'DESC')->first();
    }
}
