<?php declare(strict_types=1);

namespace App\Domains\Invoice\Store;

use App\Domains\StoreAbstract as BaseStoreAbstract;
use App\Models\Invoice as Model;

abstract class StoreAbstract extends BaseStoreAbstract
{
    /**
     * @var ?\App\Models\Invoice
     */
    protected ?Model $row;
}
