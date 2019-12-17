<?php declare(strict_types=1);

namespace App\Domains\Shipping\Store;

use App\Domains\StoreAbstract as BaseStoreAbstract;
use App\Models\Shipping as Model;

abstract class StoreAbstract extends BaseStoreAbstract
{
    /**
     * @var ?\App\Models\Shipping
     */
    protected ?Model $row;
}
