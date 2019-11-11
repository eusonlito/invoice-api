<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations;

class UserSession extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'user_session';

    /**
     * @var string
     */
    public static string $foreign = 'user_session_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): Relations\BelongsTo
    {
        return $this->belongsTo(User::class, User::$foreign);
    }
}
