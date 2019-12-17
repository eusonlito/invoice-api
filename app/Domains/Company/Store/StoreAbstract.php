<?php declare(strict_types=1);

namespace App\Domains\Company\Store;

use App\Domains\StoreAbstract as BaseStoreAbstract;
use App\Models\Company as Model;

abstract class StoreAbstract extends BaseStoreAbstract
{
    /**
     * @var ?\App\Models\Company
     */
    protected ?Model $row;
}
