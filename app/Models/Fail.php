<?php declare(strict_types=1);

namespace App\Models;

class Fail extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'fail';

    /**
     * @var string
     */
    public static string $foreign = 'fail_id';
}
