<?php declare(strict_types=1);

namespace Tests\Unit;

class CountryTest extends TestAbstract
{
    /**
     * @var string
     */
    protected string $route = 'country';

    /**
     * @var int
     */
    protected int $count = 1;

    /**
     * @var array
     */
    protected array $structure = ['id', 'iso', 'name'];

    /**
     * @return void
     */
    public function testIndexNoAuthSuccess(): void
    {
        $this->json('GET', $this->route('index'))
            ->assertStatus(200)
            ->assertJsonStructure([$this->structure])
            ->assertJsonCount($this->count);
    }

    /**
     * @return void
     */
    public function testIndexSuccess(): void
    {
        $this->auth()->json('GET', $this->route('index'))
            ->assertStatus(200)
            ->assertJsonStructure([$this->structure])
            ->assertJsonCount($this->count);
    }
}
