<?php declare(strict_types=1);

namespace App\Domains;

use Closure;

abstract class CacheAbstract
{
    /**
     * @param string $name
     * @param Closure $closure
     * @param int $time = 3600
     *
     * @return array|string
     */
    protected function cache(string $name, Closure $closure, int $time = 3600)
    {
        return cache()->tags($this->cacheTags($name))->remember($this->cacheName($name), $time, $closure);
    }

    /**
     * @param string ...$names
     *
     * @return void
     */
    protected function cacheFlush(string ...$names)
    {
        cache()->tags(array_map([$this, 'cachePrefix'], $names))->flush();
    }

    /**
     * @param string $name
     * @param mixed $response
     *
     * @return mixed
     */
    protected function cacheFlushResponse(string $name, $response)
    {
        $this->cacheFlush($name);

        return $response;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function cacheTags(string $name): string
    {
        return $this->cachePrefix(explode('\\', $name)[2]);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function cacheName(string $name): string
    {
        return md5($this->cachePrefix($name).'|'.$this->request->fullUrl());
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function cachePrefix(string $name): string
    {
        return (isset($this->user) ? (string)$this->user->company_id : '0').'|'.$name;
    }
}
