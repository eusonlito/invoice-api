<?php declare(strict_types=1);

namespace App\Domains;

use Closure;
use App\Services\Cache\User as CacheUser;

trait CacheTrait
{
    /**
     * @var \App\Services\Cache\User
     */
    protected CacheUser $cache;

    /**
     * @return self
     */
    final protected function cacheLoad(): self
    {
        $this->cache = new CacheUser($this->user);

        return $this;
    }

    /**
     * @param string $name
     * @param Closure $closure
     * @param int $time = 3600
     *
     * @return array|string
     */
    final protected function cache(string $name, Closure $closure, int $time = 3600)
    {
        return cache()->tags($this->cacheTags($name))->remember($this->cacheName($name), $time, $closure);
    }

    /**
     * @return void
     */
    final protected function cacheFlush(): void
    {
        cache()->tags($this->cache->tag())->flush();

        $this->cache->refresh();
    }

    /**
     * @param string $name
     *
     * @return array
     */
    final protected function cacheTags(string $name): array
    {
        return [$this->cache->tag(), $this->cacheTag($name)];
    }

    /**
     * @param string $name
     *
     * @return string
     */
    final protected function cacheTag(string $name): string
    {
        return $this->cachePrefix(explode('\\', $name)[2]);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    final protected function cacheName(string $name): string
    {
        return md5($this->cachePrefix($name).'|'.$this->request->fullUrl());
    }

    /**
     * @param string $name
     *
     * @return string
     */
    final protected function cachePrefix(string $name): string
    {
        return $this->cache->version().'|'.$name;
    }
}
