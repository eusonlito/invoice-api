<?php declare(strict_types=1);

namespace App\Domains\Invoice\Event;

use App\Events\EventAbstract;
use App\Models\Invoice as Model;

class Recurring extends EventAbstract
{
    /**
     * @var \App\Models\Invoice
     */
    public Model $row;

    /**
     * @param \App\Models\Invoice $row
     *
     * @return self
     */
    public function __construct(Model $row)
    {
        $this->row = $row;
    }
}
