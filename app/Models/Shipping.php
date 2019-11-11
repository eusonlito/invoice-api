<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Shipping extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'shipping';

    /**
     * @var string
     */
    public static string $foreign = 'shipping_id';

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeList(Builder $q)
    {
        $q->orderBy('name', 'ASC');
    }
}
