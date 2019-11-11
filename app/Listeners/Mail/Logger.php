<?php declare(strict_types=1);

namespace App\Listeners\Mail;

use Illuminate\Mail\Events\MessageSending;
use App\Services;

class Logger
{
    /**
     * @param \Illuminate\Mail\Events\MessageSending $event
     *
     * @return void
     */
    public function handle(MessageSending $event)
    {
        if (config('logging.channels.mail.enabled')) {
            Services\Logger\Mail::store($event);
        }
    }
}
