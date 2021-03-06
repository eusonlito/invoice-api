<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations;

class InvoiceFile extends ModelAbstract
{
    use Traits\Storage;

    /**
     * @var string
     */
    protected $table = 'invoice_file';

    /**
     * @var string
     */
    public static string $foreign = 'invoice_file_id';

    /**
     * @var array
     */
    protected $casts = [
        'main' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice(): Relations\BelongsTo
    {
        return $this->belongsTo(Invoice::class, Invoice::$foreign);
    }

    /**
     * @return ?string
     */
    public function getFileAbsoluteAttribute(): ?string
    {
        return $this->file ? static::disk()->path($this->file) : null;
    }
}
