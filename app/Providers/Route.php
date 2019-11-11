<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route as BaseRoute;

class Route extends ServiceProvider
{
    /**
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * @return void
     */
    public function boot()
    {
        Route::pattern('id', '[0-9]+');

        parent::boot();
    }

    /**
     * @return void
     */
    public function map()
    {
        $this->routesApi();
    }

    /**
     * @return void
     */
    public function routesApi()
    {
        BaseRoute::prefix('v1')
            ->middleware('logger.request')
            ->namespace($this->namespace.'\\Api')
            ->group(base_path('app/Http/routes/api.php'));
    }
}
