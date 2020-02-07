<?php declare(strict_types=1);

namespace App\Domains\InvoiceItem\Store;

use App\Domains\StoreAbstract as BaseStoreAbstract;
use App\Models\InvoiceItem as Model;

abstract class StoreAbstract extends BaseStoreAbstract
{
    /**
     * @var ?\App\Models\InvoiceItem
     */
    protected ?Model $row;
}
