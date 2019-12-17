<?php declare(strict_types=1);

namespace App\Domains\InvoiceFile\Store;

use App\Domains\StoreAbstract as BaseStoreAbstract;
use App\Models\InvoiceFile as Model;

abstract class StoreAbstract extends BaseStoreAbstract
{
    /**
     * @var ?\App\Models\InvoiceFile
     */
    protected ?Model $row;
}
