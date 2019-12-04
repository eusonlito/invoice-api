<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models;
use App\Services;

abstract class ControllerAbstract extends BaseController
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected Request $request;

    /**
     * @var ?\App\Models\User
     */
    protected ?Models\User $user;

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return self
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->user = app('user');
    }

    /**
     * @param mixed $data
     * @param int $httpStatus
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final protected function json($data, int $httpStatus = 200): JsonResponse
    {
        return Services\Request\Auth::addTokenToResponse(response()->json($data, $httpStatus, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), $data);
    }

    /**
     * @param string $code
     * @param string $message
     * @param int $httpStatus
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final protected function error(string $code, string $message, int $httpStatus): JsonResponse
    {
        return $this->json([
            'code' => $code,
            'message' => $message
        ], $httpStatus);
    }
}
