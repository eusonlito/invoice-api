<?php declare(strict_types=1);

namespace App\Domains;

use Illuminate\Http\JsonResponse;
use App\Services\Request\Auth;

abstract class ControllerApiAbstract extends ControllerAbstract
{
    /**
     * @param string $name
     * @param mixed $data
     *
     * @return ?array
     */
    protected function fractal(string $name, $data): ?array
    {
        return forward_static_call([static::FRACTAL, 'transform'], $name, $data);
    }

    /**
     * @param string $class
     * @param string $name
     * @param mixed $data
     *
     * @return ?array
     */
    protected function fractalFrom(string $class, string $name, $data): ?array
    {
        return forward_static_call([__NAMESPACE__.'\\'.$class.'\\Fractal', 'transform'], $name, $data);
    }

    /**
     * @param mixed $data
     * @param int $httpStatus
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final protected function json($data, int $httpStatus = 200): JsonResponse
    {
        return Auth::addTokenToResponse(response()->json($data, $httpStatus, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), $data);
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
