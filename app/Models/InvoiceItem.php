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
     * @var array
     */
    protected $casts = [
        'line' => 'integer',
        'quantity' => 'float',
        'percent_discount' => 'integer',
        'percent_tax' => 'float',
        'amount_price' => 'float',
        'amount_discount' => 'float',
        'amount_tax' => 'float',
        'amount_subtotal' => 'float',
        'amount_total' => 'float',
    ];

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
