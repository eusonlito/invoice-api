<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations;
use Illuminate\Database\Eloquent\Builder;

class InvoiceStatus extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'invoice_status';

    /**
     * @var string
     */
    public static string $foreign = 'invoice_status_id';

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
        $q->orderBy('order', 'ASC');
    }
}
