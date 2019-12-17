<?php declare(strict_types=1);

namespace App\Domains\Tax\Store;

use App\Domains\StoreAbstract as BaseStoreAbstract;
use App\Models\Tax as Model;

abstract class StoreAbstract extends BaseStoreAbstract
{
    /**
     * @var ?\App\Models\Tax
     */
    protected ?Model $row;
}
