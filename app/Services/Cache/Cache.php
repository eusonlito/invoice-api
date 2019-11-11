<?php declare(strict_types=1);

namespace App\Services\Cache;

use Closure;
use Illuminate\Support\Facades\Cache as BaseCache;

class Cache
{
    /**
     * @param string $key
     * @param int $time
     * @param \Closure $callback
     *
     * @return mixed
     */
    public static function remember(string $key, int $time, Closure $callback)
    {
        return BaseCache::remember($key, $time, $callback);
    }
}
