<?php declare(strict_types=1);

namespace App\Domains\Invoice\Listener;

use App\Domains\Invoice\Store;
use App\Domains\Invoice\Event\Recurring as Event;
use App\Listeners\ListenerAbstract;
use App\Models\Invoice as Model;

class Recurring extends ListenerAbstract
{
    /**
     * @param \App\Domains\Invoice\Event\Recurring $event
     *
     * @return void
     */
    public function handle(Event $event)
    {
        $row = Model::detail()->findOrFail($event->id);

        (new Store($row->user, $row))->recurring();
    }
}
