<?php declare(strict_types=1);

namespace App\Domains\Cache;

use App\Domains\RequestAbstract;
use App\Services\Cache\User as CacheUser;

class Request extends RequestAbstract
{
    /**
     * @return string
     */
    public function version(): string
    {
        return $this->cache->version();
    }

    /**
     * @return string
     */
    public function versionCached(): string
    {
        return (string)$this->cache(__METHOD__, fn () => $this->version());
    }
}
