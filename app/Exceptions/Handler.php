<?php declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use App\Domain\User\StoreAuth;
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
     * @param  \Exception  $e
     *
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);

        if (!$this->shouldReport($e)) {
            return;
        }

        $this->reportRequest($e);
        $this->reportSentry($e);
    }

    /**
     * @param \Exception $e
     *
     * @return void
     */
    protected function reportRequest(Exception $e)
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
     * @param \Exception $e
     *
     * @return void
     */
    protected function reportSentry(Exception $e)
    {
        if (app()->bound('sentry')) {
            app('sentry')->captureException($e);
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  mixed  $request
     * @param  \Exception  $e
     *
     * @return \Symfony\Component\HttpFoundation\Response|void
     */
    public function render($request, Exception $e)
    {
        return $this->renderJson($e);
    }

    /**
     * Render an exception into an JSON HTTP response.
     *
     * @param  \Exception  $e
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function renderJson(Exception $e): JsonResponse
    {
        $e = ResponseException::fromException($e);

        $response = response()->json([
            'code' => $e->getCode(),
            'status' => $e->getStatus(),
            'message' => $e->getMessage(),
        ], $e->getCode());

        if ($token = StoreAuth::token()) {
            $response->header('Authorization', 'Bearer '.$token);
        }

        return $response;
    }
}
