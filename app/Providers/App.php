<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\VarDumper\VarDumper;
use App\Models;
use App\Services;

class App extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->queues();
        $this->handlers();
    }

    /**
     * @return void
     */
    public function register()
    {
        $this->singletons();
    }

    /**
     * @return void
     */
    protected function singletons()
    {
        $this->app->singleton('configuration', static fn () =>Models\Configuration::pluck('value', 'key'));
        $this->app->singleton('language', static fn () => null);
        $this->app->singleton('user', static fn () => auth()->user());
    }

    /**
     * @return void
     */
    protected function queues()
    {
        Queue::failing(static function (JobFailed $event) {
            Log::error($event->exception->getMessage());
        });
    }

    /**
     * @return void
     */
    protected function handlers()
    {
        VarDumper::setHandler([Services\Logger\Dump::class, 'dump']);
    }
}
