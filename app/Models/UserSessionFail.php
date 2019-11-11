<?php declare(strict_types=1);

namespace App\Models;

class UserSessionFail extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'user_session_fail';

    /**
     * @var string
     */
    public static string $foreign = 'user_session_fail_id';
}
