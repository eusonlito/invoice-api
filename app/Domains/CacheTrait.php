<?php declare(strict_types=1);

namespace App\Domains;

use Closure;
use App\Services\Cache\Domain as Cache;

trait CacheTrait
{
    /**
     * @var ?\App\Services\Cache\Domain = null
     */
    protected ?Cache $cache = null;

    /**
     * @return self
     */
    final protected function cacheLoad(): self
    {
        if (config('cache.enabled') && ($ttl = config('cache.ttl'))) {
            $this->cache = new Cache($this->request, $this->user, $ttl);
        }

        return $this;
    }

    /**
     * @param string $name
     * @param Closure $closure
     *
     * @return mixed
     */
    final protected function cache(string $name, Closure $closure)
    {
        if ($this->cache) {
            return $this->cache->get($name, $closure);
        }

        return $closure();
    }

    /**
     * @return void
     */
    final protected function cacheFlush(): void
    {
        if ($this->cache) {
            $this->cache->flush();
        }
    }

    /**
     * @return string
     */
    final protected function cacheVersion(): string
    {
        if ($this->cache) {
            return $this->cache->version();
        }

        return '';
    }
}
