<?php declare(strict_types=1);

namespace App\Services\Request;

use App\Models;

class Fail
{
    /**
     * @param string $action
     * @param int $time = 60
     *
     * @return int
     */
    public static function count(string $action, int $time = 60): int
    {
        return Models\Fail::where('ip', request()->ip())
            ->where('action', $action)
            ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-'.$time.' seconds')))
            ->count();
    }

    /**
     * @param string $action
     * @param string $value
     *
     * @return void
     */
    public static function add(string $action, string $value)
    {
        Models\Fail::insert([
            'action' => $action,
            'value' => $value,
            'ip' => request()->ip(),
        ]);
    }
}
