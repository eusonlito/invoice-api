<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Language extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'language';

    /**
     * @var string
     */
    public static string $foreign = 'language_id';

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeList(Builder $q)
    {
        $q->enabled()->orderBy('order', 'ASC');
    }
}
