<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations;
use Illuminate\Database\Eloquent\Builder;

class InvoiceRecurring extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'invoice_recurring';

    /**
     * @var string
     */
    public static string $foreign = 'invoice_recurring_id';

    /**
     * @var array
     */
    protected $casts = [
        'enabled' => 'boolean',
    ];

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

    /**
     * @param string $date
     *
     * @return string
     */
    public function next(string $date): string
    {
        return date('Y-m-d', strtotime($date.' +1 '.$this->every));
    }
}
