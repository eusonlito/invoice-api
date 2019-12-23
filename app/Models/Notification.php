<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations;

class Notification extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'notification';

    /**
     * @var string
     */
    public static string $foreign = 'notification_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): Relations\BelongsTo
    {
        return $this->belongsTo(Company::class, Company::$foreign);
    }

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
    public function scopeList(Builder $q)
    {
        $q->orderBy('created_at', 'DESC');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeUnread(Builder $q)
    {
        $q->whereNull('readed_at');
    }
}
