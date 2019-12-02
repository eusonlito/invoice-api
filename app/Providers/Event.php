<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Domains;
use App\Listeners;

class Event extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Domains\Invoice\Event\Recurring::class => [Domains\Invoice\Listener\Recurring::class],
        MessageSending::class => [Listeners\Mail\Logger::class],
    ];
}
