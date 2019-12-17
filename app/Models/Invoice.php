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
     * @var array
     */
    protected $casts = [
        'quantity' => 'integer',

        'percent_discount' => 'float',
        'percent_tax' => 'float',

        'amount_subtotal' => 'float',
        'amount_discount' => 'float',
        'amount_tax' => 'float',
        'amount_shipping' => 'float',
        'amount_total' => 'float',
        'amount_paid' => 'float',
        'amount_due' => 'float',
    ];

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
    public function recurring(): Relations\BelongsTo
    {
        return $this->belongsTo(InvoiceRecurring::class, InvoiceRecurring::$foreign);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function serie(): Relations\BelongsTo
    {
        return $this->belongsTo(InvoiceSerie::class, InvoiceSerie::$foreign);
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): Relations\BelongsTo
    {
        return $this->belongsTo(User::class, User::$foreign);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     * @param string $mode
     *
     * @return void
     */
    public function scopeOrder(Builder $q, string $mode)
    {
        $q->orderBy('date_at', $mode)->orderBy('number', $mode);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeList(Builder $q)
    {
        $q->simple()->with(['serie', 'status'])->order('DESC');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopePendingToRecurring(Builder $q)
    {
        $q->where('recurring_at', '<=', date('Y-m-d'))
            ->whereIn('invoice_recurring_id', InvoiceRecurring::select('id')->enabled());
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
            'payment', 'recurring', 'shipping', 'serie', 'status', 'tax'
        ]);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeExport(Builder $q)
    {
        $q->detail()
            ->with(['client', 'items' => static fn ($q) => $q->with(['product'])])
            ->order('ASC');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeExportCsv(Builder $q)
    {
        $q->detail()->order('ASC');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeExportZip(Builder $q)
    {
        $q->detail()->with(['file', 'user'])->order('ASC');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     * @param array $input
     *
     * @return void
     */
    public function scopeFilterByInput(Builder $q, array $input)
    {
        if ($filter = $input['date_start'] ??= false) {
            $q->where('date_at', '>=', dateToDate($filter));
        }

        if ($filter = $input['date_end'] ??= false) {
            $q->where('date_at', '<=', dateToDate($filter));
        }

        if ($filter = $input['invoice_recurring_id'] ??= false) {
            $q->where('invoice_recurring_id', (int)$filter);
        }

        if ($filter = $input['invoice_serie_id'] ??= false) {
            $q->where('invoice_serie_id', (int)$filter);
        }

        if ($filter = $input['invoice_status_id'] ??= false) {
            $q->where('invoice_status_id', (int)$filter);
        }

        if ($filter = $input['payment_id'] ??= false) {
            $q->where('payment_id', (int)$filter);
        }
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
            'invoice_recurring_id',
            'invoice_serie_id',
            'invoice_status_id',
            'payment_id',
            'shipping_id',
            'tax_id',
            'user_id'
        );
    }
}
