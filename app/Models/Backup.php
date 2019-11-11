<?php declare(strict_types=1);

namespace App\Models;

class Backup extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'backup';

    /**
     * @var string
     */
    public static string $foreign = 'backup_id';
}
