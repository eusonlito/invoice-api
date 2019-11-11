<?php declare(strict_types=1);

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        Middlewares\Language::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'user' => [
            Middlewares\UserAuth::class,
            Middlewares\UserLocale::class
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'language' => Middlewares\Language::class,

        'logger.request' => Middlewares\LoggerRequest::class,

        'user.auth' => Middlewares\UserAuth::class,
        'user.company' => Middlewares\UserCompany::class,
        'user.confirm' => Middlewares\UserConfirm::class,
        'user.locale' => Middlewares\UserLocale::class,
        'user.refresh' => Middlewares\UserRefresh::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        Middlewares\LoggerRequest::class,
        Middlewares\Language::class,
        Middlewares\UserAuth::class,
        Middlewares\UserLocale::class,
        Middlewares\UserConfirm::class,
        Middlewares\UserRefresh::class,
    ];
}
