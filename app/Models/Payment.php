<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations;

class Payment extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'payment';

    /**
     * @var string
     */
    public static string $foreign = 'payment_id';

    /**
     * @var array
     */
    protected $casts = [
        'default' => 'boolean',
        'enabled' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clients(): Relations\HasMany
    {
        return $this->hasMany(Client::class, static::$foreign);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices(): Relations\HasMany
    {
        return $this->hasMany(Invoice::class, static::$foreign);
    }

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
