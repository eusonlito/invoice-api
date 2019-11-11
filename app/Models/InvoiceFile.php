<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Build;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Support\Facades\Storage;

class InvoiceFile extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'invoice_file';

    /**
     * @var string
     */
    public static string $foreign = 'invoice_file_id';

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
        return $this->file ? Storage::disk('private')->path($this->file) : null;
    }
}
