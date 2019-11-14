<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations;

class Invoice extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'invoice';

    /**
     * @var string
     */
    public static string $foreign = 'invoice_id';

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
    public function clientAddressBilling(): Relations\BelongsTo
    {
        return $this->belongsTo(ClientAddress::class, 'client_address_billing_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clientAddressShipping(): Relations\BelongsTo
    {
        return $this->belongsTo(ClientAddress::class, 'client_address_shipping_id');
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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function file(): Relations\HasOne
    {
        return $this->hasOne(InvoiceFile::class, static::$foreign)->where('main', true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files(): Relations\HasMany
    {
        return $this->hasMany(InvoiceFile::class, static::$foreign)->orderBy('main', 'DESC');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): Relations\HasMany
    {
        return $this->hasMany(InvoiceItem::class, static::$foreign)->orderBy('line', 'ASC');
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
    public function status(): Relations\BelongsTo
    {
        return $this->belongsTo(InvoiceStatus::class, InvoiceStatus::$foreign);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tax(): Relations\BelongsTo
    {
        return $this->belongsTo(Tax::class, Tax::$foreign);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeList(Builder $q)
    {
        $q->simple()->with(['status'])->orderBy('date_at', 'DESC');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeDetail(Builder $q)
    {
        $q->with([
            'clientAddressBilling', 'clientAddressShipping', 'discount', 'files', 'items',
            'payment', 'shipping', 'status', 'tax'
        ]);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeExport(Builder $q)
    {
        $q->detail()->with(['client', 'items' => static function ($q) {
            $q->with(['product']);
        }]);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeSimple(Builder $q)
    {
        $q->select(
            'id',
            'number',
            'billing_name',
            'amount_total',
            'amount_paid',
            'amount_due',
            'date_at',
            'paid_at',
            'company_id',
            'client_address_billing_id',
            'client_address_shipping_id',
            'discount_id',
            'invoice_status_id',
            'payment_id',
            'shipping_id',
            'tax_id',
            'user_id'
        );
    }
}
