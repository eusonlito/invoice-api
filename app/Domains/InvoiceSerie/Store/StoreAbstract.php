<?php declare(strict_types=1);

namespace App\Domains\InvoiceSerie\Store;

use App\Domains\StoreAbstract as BaseStoreAbstract;
use App\Models\InvoiceSerie as Model;

abstract class StoreAbstract extends BaseStoreAbstract
{
    /**
     * @var ?\App\Models\InvoiceSerie
     */
    protected ?Model $row;
}
