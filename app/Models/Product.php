<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Product extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'product';

    /**
     * @var string
     */
    public static string $foreign = 'product_id';

    /**
     * @var array
     */
    protected $casts = [
        'price' => 'float',
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
