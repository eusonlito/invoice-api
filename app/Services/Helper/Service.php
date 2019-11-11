<?php declare(strict_types=1);

namespace App\Services\Helper;

use Closure;
use App\Models;
use App\Services;

class Service
{
    /**
     * @param string $key
     * @param int $time
     * @param \Closure $callback
     *
     * @return mixed
     */
    public function cache(string $key, int $time, Closure $callback)
    {
        return Services\Cache\Cache::remember($key, $time, $callback);
    }

    /**
     * @param string $action
     * @param \App\Models\ModelAbstract $row
     * @param int $user_id
     *
     * @return \App\Models\Backup
     */
    public function backup(string $action, Models\ModelAbstract $row, int $user_id): Models\Backup
    {
        return Services\Backup\Backup::store($action, $row, $user_id);
    }

    /**
     * @param string $table
     * @param string $action
     * @param int $user_id
     * @param array $ids = []
     *
     * @return \App\Models\Log
     */
    public function log(string $table, string $action, int $user_id, array $ids = []): Models\Log
    {
        return Services\Backup\Backup::log($table, $action, $user_id, $ids);
    }
}
