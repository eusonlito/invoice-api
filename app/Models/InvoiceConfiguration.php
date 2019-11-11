<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class InvoiceConfiguration extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'invoice_configuration';

    /**
     * @var string
     */
    public static string $foreign = 'invoice_configuration_id';

    /**
     * @param \Illuminate\Database\Eloquent\Builder $q
     *
     * @return void
     */
    public function scopeList(Builder $q)
    {
        $q->orderBy('key', 'ASC');
    }
}
