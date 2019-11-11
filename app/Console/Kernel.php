<?php declare(strict_types=1);

namespace App\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * @var array
     */
    protected $commands = [];

    /**
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
