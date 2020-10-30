<?php declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use App\Domains\User\Store;
use App\Services;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Tymon\JWTAuth\Exceptions\JWTException::class,
        \App\Exceptions\AuthenticationException::class,
        \App\Exceptions\ValidatorException::class,
        \App\Exceptions\NotAllowedException::class,
    ];

    /**
     * @var array
     */
    protected $dontReportRequest = [];

    /**
     * Report an exception
     *
     * @param  \Throwable  $e
     *
     * @return void
     */
    public function report(Throwable $e)
    {
        parent::report($e);

        if (!$this->shouldReport($e)) {
            return;
        }

        $this->reportRequest($e);
        $this->reportSentry($e);
    }

    /**
     * @param \Throwable $e
     *
     * @return void
     */
    protected function reportRequest(Throwable $e)
    {
        if (config('logging.channels.request.enabled') !== true) {
            return;
        }

        $request = request();

        Services\Logger\Request::error($request->url(), [
            'headers' => $request->headers->all(),
            'input' => $request->except('password'),
            'class' => get_class($e),
            'code' => (method_exists($e, 'getStatusCode') ? $e->getStatusCode() : $e->getCode()),
            'status' => (method_exists($e, 'getStatus') ? $e->getStatus() : null),
            'message' => $e->getMessage(),
        ]);
    }

    /**
     * @param \Throwable $e
     *
     * @return void
     */
    protected function reportSentry(Throwable $e)
    {
        if (app()->bound('sentry')) {
            app('sentry')->captureException($e);
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  mixed  $request
     * @param  \Throwable  $e
     *
     * @return \Symfony\Component\HttpFoundation\Response|void
     */
    public function render($request, Throwable $e)
    {
        return $this->renderJson($e);
    }

    /**
     * Render an exception into an JSON HTTP response.
     *
     * @param  \Throwable  $e
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function renderJson(Throwable $e): JsonResponse
    {
        $e = ResponseException::fromException($e);

        $response = response()->json([
            'code' => $e->getCode(),
            'status' => $e->getStatus(),
            'message' => $e->getMessage(),
        ], $e->getCode());

        if ($token = (new Store())->authToken()) {
            $response->header('Authorization', 'Bearer '.$token);
        }

        return $response;
    }
}
