<?php declare(strict_types=1);

namespace App\Domains\Product\Store;

use App\Domains\StoreAbstract as BaseStoreAbstract;
use App\Models\Product as Model;

abstract class StoreAbstract extends BaseStoreAbstract
{
    /**
     * @var ?\App\Models\Product
     */
    protected ?Model $row;
}
