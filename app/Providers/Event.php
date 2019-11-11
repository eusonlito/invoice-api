<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Listeners;

class Event extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        MessageSending::class => [Listeners\Mail\Logger::class],
    ];
}
