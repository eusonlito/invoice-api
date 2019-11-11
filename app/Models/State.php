<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations;

class State extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'state';

    /**
     * @var string
     */
    public static string $foreign = 'state_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country(): Relations\BelongsTo
    {
        return $this->belongsTo(Country::class, Country::$foreign);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeList(Builder $q, int $country_id)
    {
        $q->where('country_id', $country_id)->orderBy('name', 'ASC');
    }
}
