<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations;

class Log extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'log';

    /**
     * @var string
     */
    public static string $foreign = 'log_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromUser(): Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): Relations\BelongsTo
    {
        return $this->belongsTo(User::class, User::$foreign);
    }
}
