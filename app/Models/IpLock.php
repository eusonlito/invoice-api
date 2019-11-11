<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class IpLock extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'ip_lock';

    /**
     * @var string
     */
    public static string $foreign = 'ip_lock_id';

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeCurrent(Builder $q)
    {
        $q->where(static function ($q) {
            $q->orWhere('end_at', '>=', date('Y-m-d H:i:s'))->orWhereNull('end_at');
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopePrevious(Builder $q)
    {
        $q->where('end_at', '<', date('Y-m-d H:i:s'));
    }
}
