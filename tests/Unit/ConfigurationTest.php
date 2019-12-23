<?php declare(strict_types=1);

namespace Tests\Unit;

class ConfigurationTest extends TestAbstract
{
    /**
     * @var string
     */
    protected string $route = 'configuration';

    /**
     * @var int
     */
    protected int $count = 0;

    /**
     * @return void
     */
    public function testIndexNoAuthSuccess(): void
    {
        $this->json('GET', $this->route('index'))
            ->assertStatus(200)
            ->assertJsonCount($this->count);
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
}
