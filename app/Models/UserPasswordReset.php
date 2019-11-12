<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations;

class UserPasswordReset extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'user_password_reset';

    /**
     * @var string
     */
    public static string $foreign = 'user_password_reset_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): Relations\BelongsTo
    {
        return $this->belongsTo(User::class, User::$foreign);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeAvailable(Builder $q)
    {
        $q->whereNull(['canceled_at', 'finished_at']);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeSimple(Builder $q)
    {
        $q->select('id', 'hash', 'ip', 'finished_at', 'canceled_at', 'created_at', 'user_id');
    }
}
