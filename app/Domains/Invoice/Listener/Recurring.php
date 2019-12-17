<?php declare(strict_types=1);

namespace App\Domains\Invoice\Listener;

use App\Domains\Invoice\Store;
use App\Domains\Invoice\Event\Recurring as Event;
use App\Listeners\ListenerAbstract;

class Recurring extends ListenerAbstract
{
    /**
     * @param \App\Domains\Invoice\Event\Recurring $event
     *
     * @return void
     */
    public function handle(Event $event)
    {
        (new Store($event->row->user, $event->row))->recurring();
    }
}
