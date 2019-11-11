<?php declare(strict_types=1);

namespace App\Services\Logger;

use Illuminate\Mail\Events\MessageSending;

class Mail
{
    /**
     * @param \Illuminate\Mail\Events\MessageSending $event
     *
     * @return void
     */
    public static function store(MessageSending $event)
    {
        static::log($event);
    }

    /**
     * @param \Illuminate\Mail\Events\MessageSending $event
     *
     * @return void
     */
    protected static function log(MessageSending $event)
    {
        $file = storage_path('logs/mails/'.date('Y-m-d/H-i-s').'-'.uniqid().'.log');
        $dir = dirname($file);

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($file, (string)$event->message);
    }
}
