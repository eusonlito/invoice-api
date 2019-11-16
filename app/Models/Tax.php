<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Tax extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'tax';

    /**
     * @var string
     */
    public static string $foreign = 'tax_id';

    /**
     * @var array
     */
    protected $casts = [
        'value' => 'float',
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
        $q->orderBy('name', 'ASC');
    }
}
