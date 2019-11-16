<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations;

class ClientAddress extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'client_address';

    /**
     * @var string
     */
    public static string $foreign = 'client_address_id';

    /**
     * @var array
     */
    protected $casts = [
        'billing' => 'boolean',
        'shipping' => 'boolean',
        'enabled' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client(): Relations\BelongsTo
    {
        return $this->belongsTo(Client::class, Client::$foreign);
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
        $q->orderBy('name', 'ASC');
    }
}
