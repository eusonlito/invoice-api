<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations;

class InvoiceSerie extends ModelAbstract
{
    use Traits\Storage;

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
        $q->orderBy('default', 'DESC');
    }

    /**
     * @return string
     */
    public function getCertificateFilePublicAttribute(): string
    {
        if (empty($this->certificate_file)) {
            return '';
        }

        return explode('-', basename($this->certificate_file), 2)[1];
    }

    /**
     * @return string
     */
    public function getNumberValueAttribute(): string
    {
        $number = $this->number_next;

        if ($this->number_fill) {
            $number = sprintf('%0'.$this->number_fill.'d', $number);
        }

        if ($this->number_prefix) {
            $number = $this->number_prefix.$number;
        }

        return $number;
    }
}
