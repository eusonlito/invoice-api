<?php declare(strict_types=1);

namespace App\Domains\User\Store;

use App\Domains\StoreAbstract as BaseStoreAbstract;
use App\Models\User as Model;

abstract class StoreAbstract extends BaseStoreAbstract
{
    /**
     * @var ?\App\Models\User
     */
    protected ?Model $row;
}
