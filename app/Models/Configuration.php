<?php declare(strict_types=1);

namespace App\Models;

class Configuration extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'configuration';

    /**
     * @var string
     */
    public static string $foreign = 'configuration_id';
}
