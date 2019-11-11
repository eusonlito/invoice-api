<?php declare(strict_types=1);

namespace App\Services\Request;

use App\Exceptions;
use App\Models;

class IpLock
{
    /**
     * @param bool $exception = false
     *
     * @throws \App\Exceptions\ValidatorException
     *
     * @return bool
     */
    public static function locked(bool $exception = false): bool
    {
        if (Models\IpLock::where('ip', request()->ip())->current()->count() === 0) {
            return false;
        }

        if ($exception === false) {
            return true;
        }

        throw new Exceptions\ValidatorException(__('exception.ip-lock', ['seconds' => config('app.ip_lock_time')]));
    }

    /**
     * @param string $action
     * @param string $value
     * @param int $limit = 4
     * @param ?int $time = 60
     *
     * @return void
     */
    public static function lockIfFail(string $action, string $value, int $limit = 4, ?int $time = 60)
    {
        Fail::add($action, $value);

        if (Fail::count($action) > $limit) {
            static::add($time);
        }
    }

    /**
     * @param ?int $time = 60
     *
     * @return void
     */
    public static function add(?int $time = 60)
    {
        if ($time !== null) {
            $time = $time ?: config('auth.lock.time');
            $time = date('Y-m-d H:i:s', strtotime('+'.$time.' seconds'));
        }

        Models\IpLock::insert([
            'ip' => request()->ip(),
            'end_at' => $time
        ]);
    }
}
