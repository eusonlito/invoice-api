<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations;

class Client extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'client';

    /**
     * @var string
     */
    public static string $foreign = 'client_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses(): Relations\HasMany
    {
        return $this->hasMany(ClientAddress::class, static::$foreign);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): Relations\BelongsTo
    {
        return $this->belongsTo(Company::class, Company::$foreign);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function discount(): Relations\BelongsTo
    {
        return $this->belongsTo(Discount::class, Discount::$foreign);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices(): Relations\HasMany
    {
        return $this->hasMany(Invoice::class, static::$foreign);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment(): Relations\BelongsTo
    {
        return $this->belongsTo(Payment::class, Payment::$foreign);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shipping(): Relations\BelongsTo
    {
        return $this->belongsTo(Shipping::class, Shipping::$foreign);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tax(): Relations\BelongsTo
    {
        return $this->belongsTo(Tax::class, Tax::$foreign);
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

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeDetail(Builder $q)
    {
        $q->with(['discount', 'payment', 'shipping', 'tax']);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeExport(Builder $q)
    {
        $q->detail()->with(['addresses']);
    }
}
