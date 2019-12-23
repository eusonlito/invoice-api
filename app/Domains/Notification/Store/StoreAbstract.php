<?php declare(strict_types=1);

namespace App\Domains\Notification\Store;

use App\Domains\StoreAbstract as BaseStoreAbstract;
use App\Models\Notification as Model;

abstract class StoreAbstract extends BaseStoreAbstract
{
    /**
     * @var ?\App\Models\Notification
     */
    protected ?Model $row;
}
