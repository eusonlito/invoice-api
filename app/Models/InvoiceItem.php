<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations;

class InvoiceItem extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'invoice_item';

    /**
     * @var string
     */
    public static string $foreign = 'invoice_item_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice(): Relations\BelongsTo
    {
        return $this->belongsTo(Invoice::class, Invoice::$foreign);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): Relations\BelongsTo
    {
        return $this->belongsTo(Product::class, Product::$foreign);
    }
}
