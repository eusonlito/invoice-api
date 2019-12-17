<?php declare(strict_types=1);

namespace App\Services\Request;

use Illuminate\Http\JsonResponse;
use App\Domains\User\Store;

class Auth
{
    /**
     * @param \Illuminate\Http\JsonResponse $response
     * @param mixed $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function addTokenToResponse(JsonResponse $response, $data): JsonResponse
    {
        if (is_array($data) === false || empty($data['token'])) {
            $token = (new Store())->authToken();
        } else {
            $token = $data['token'];
        }

        if ($token) {
            $response->header('Authorization', 'Bearer '.$token);
        }

        return $response;
    }
}
