<?php declare(strict_types=1);

namespace App\Domains\Discount\Store;

use App\Domains\StoreAbstract as BaseStoreAbstract;
use App\Models\Discount as Model;

abstract class StoreAbstract extends BaseStoreAbstract
{
    /**
     * @var ?\App\Models\Discount
     */
    protected ?Model $row;
}
