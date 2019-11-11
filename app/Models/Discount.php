<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Discount extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'discount';

    /**
     * @var string
     */
    public static string $foreign = 'discount_id';

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
