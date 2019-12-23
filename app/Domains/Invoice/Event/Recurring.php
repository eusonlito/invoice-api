<?php declare(strict_types=1);

namespace App\Domains\Invoice\Event;

use App\Events\EventAbstract;

class Recurring extends EventAbstract
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @param int $id
     *
     * @return self
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
