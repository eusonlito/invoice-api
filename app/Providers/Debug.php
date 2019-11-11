<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services;

class Debug extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        if (config('logging.channels.database.enabled')) {
            Services\Logger\Database::listen();
        }
    }
}
