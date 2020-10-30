<?php declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\Tool\OPcache\Service\Preloader;

class ToolOpcachePreload extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'tool:opcache:preload';

    /**
     * @var string
     */
    protected $description = 'Preload OPcache';

    /**
     * @return void
     */
    public function handle()
    {
        return (new Preloader(base_path('')))
            ->paths(
                base_path('app'),
                base_path('vendor/laravel'),
            )
            ->ignore(
                'Illuminate\Http\Testing',
                'Illuminate\Filesystem\Cache',
                'Illuminate\Foundation\Testing',
                'Illuminate\Testing',
                'PHPUnit',
            )
            ->load()
            ->log();
    }
}
