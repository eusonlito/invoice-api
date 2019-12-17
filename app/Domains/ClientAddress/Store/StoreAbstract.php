<?php declare(strict_types=1);

namespace App\Domains\ClientAddress\Store;

use App\Domains\StoreAbstract as BaseStoreAbstract;
use App\Models\ClientAddress as Model;

abstract class StoreAbstract extends BaseStoreAbstract
{
    /**
     * @var ?\App\Models\ClientAddress
     */
    protected ?Model $row;
}
