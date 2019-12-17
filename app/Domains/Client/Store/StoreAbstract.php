<?php declare(strict_types=1);

namespace App\Domains\Client\Store;

use App\Domains\StoreAbstract as BaseStoreAbstract;
use App\Models\Client as Model;

abstract class StoreAbstract extends BaseStoreAbstract
{
    /**
     * @var ?\App\Models\Client
     */
    protected ?Model $row;
}
