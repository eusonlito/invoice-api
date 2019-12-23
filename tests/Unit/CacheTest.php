<?php declare(strict_types=1);

namespace Tests\Unit;

class CacheTest extends TestAbstract
{
    /**
     * @var string
     */
    protected string $route = 'cache';

    /**
     * @return void
     */
    public function testVersionNoAuthSuccess(): void
    {
        $content = $this->get($this->route('version'))
            ->assertStatus(200)
            ->getContent();

        $this->assertEquals($content, '1');
    }

    /**
     * @return void
     */
    public function testVersionSuccess(): void
    {
        $content = $this->get($this->route('version'))
            ->assertStatus(200)
            ->getContent();

        $this->assertEquals($content, '1');
    }
}
