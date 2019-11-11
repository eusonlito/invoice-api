<?php declare(strict_types=1);

namespace App\Services\Backup;

use App\Models;

class Backup
{
    /**
     * @param string $action
     * @param \App\Models\ModelAbstract $row
     * @param int $user_id
     *
     * @return \App\Models\Backup
     */
    public static function store(string $action, Models\ModelAbstract $row, int $user_id): Models\Backup
    {
        return Models\Backup::create([
            'action' => $action,
            'related_id' => $row->id,
            'related_table' => $row->getTable(),
            'content' => $row->toJson(),
            'user_id' => $user_id,
        ]);
    }

    /**
     * @param string $table
     * @param string $action
     * @param int $user_id
     * @param array $ids = []
     *
     * @return \App\Models\Log
     */
    public static function log(string $table, string $action, int $user_id, array $ids = []): Models\Log
    {
        return Models\Log::create([
            'table' => $table,
            'action' => $action,
            'from_user_id' => $user_id,
        ] + array_filter(array_map('intval', $ids)));
    }
}
