<?php declare(strict_types=1);

namespace App\Domains\InvoiceRecurring\Store;

use App\Domains\StoreAbstract as BaseStoreAbstract;
use App\Models\InvoiceRecurring as Model;

abstract class StoreAbstract extends BaseStoreAbstract
{
    /**
     * @var ?\App\Models\InvoiceRecurring
     */
    protected ?Model $row;
}
