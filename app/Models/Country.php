<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations;

class Country extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'country';

    /**
     * @var string
     */
    public static string $foreign = 'country_id';

    /**
     * @var array
     */
    protected $casts = [
        'default' => 'boolean',
        'enabled' => 'boolean',
    ];

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeList(Builder $q)
    {
        $q->enabled()->orderBy('default', 'DESC')->orderBy('name', 'ASC');
    }
}
