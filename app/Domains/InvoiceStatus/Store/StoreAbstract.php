<?php declare(strict_types=1);

namespace App\Domains\InvoiceStatus\Store;

use App\Domains\StoreAbstract as BaseStoreAbstract;
use App\Models\InvoiceStatus as Model;

abstract class StoreAbstract extends BaseStoreAbstract
{
    /**
     * @var ?\App\Models\InvoiceStatus
     */
    protected ?Model $row;
}
