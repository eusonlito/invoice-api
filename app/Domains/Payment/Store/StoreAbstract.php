<?php declare(strict_types=1);

namespace App\Domains\Payment\Store;

use App\Domains\StoreAbstract as BaseStoreAbstract;
use App\Models\Payment as Model;

abstract class StoreAbstract extends BaseStoreAbstract
{
    /**
     * @var ?\App\Models\Payment
     */
    protected ?Model $row;
}
