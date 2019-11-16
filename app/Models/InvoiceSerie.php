<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations;

class InvoiceSerie extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'invoice_serie';

    /**
     * @var string
     */
    public static string $foreign = 'invoice_serie_id';

    /**
     * @var array
     */
    protected $casts = [
        'value' => 'float',
        'default' => 'boolean',
        'enabled' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): Relations\BelongsTo
    {
        return $this->belongsTo(Company::class, Company::$foreign);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeList(Builder $q)
    {
        $q->orderBy('default', 'DESC');
    }
}
