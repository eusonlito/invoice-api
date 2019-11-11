<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Services\Model\User\Request;

class User extends ControllerAbstract
{
    /**
     * POST /user
     *
     * @uses POST array
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signup(): JsonResponse
    {
        return $this->json($this->request()->signup());
    }

    /**
     * POST /user/confirm
     *
     * @uses POST $user string
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmStart(): JsonResponse
    {
        return $this->json($this->request()->confirmStart());
    }

    /**
     * POST /user/confirm/{hash}
     *
     * @param string $hash
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmFinish(string $hash): JsonResponse
    {
        return $this->json($this->request()->confirmFinish($hash));
    }

    /**
     * GET /user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(): JsonResponse
    {
        return $this->json($this->request()->detailCached());
    }

    /**
     * POST /user/auth
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authCredentials(): JsonResponse
    {
        return $this->json($this->request()->authCredentials());
    }

    /**
     * GET /user/auth/refresh
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authRefresh(): JsonResponse
    {
        return $this->json($this->request()->authRefresh());
    }

    /**
     * GET /user/auth/logout
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authLogout(): JsonResponse
    {
        return $this->json($this->request()->authLogout());
    }

    /**
     * POST /user/password/reset
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function passwordResetStart(): JsonResponse
    {
        return $this->json($this->request()->passwordResetStart());
    }

    /**
     * POST /user/password/reset/{hash}
     *
     * @param string $hash
     *
     * @uses POST string $password
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function passwordResetFinish(string $hash): JsonResponse
    {
        return $this->json($this->request()->passwordResetFinish($hash));
    }

    /**
     * PATCH /user
     *
     * @uses POST array
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(): JsonResponse
    {
        return $this->json($this->request()->updateProfile());
    }

    /**
     * PATCH /user/password
     *
     * @uses POST array
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(): JsonResponse
    {
        return $this->json($this->request()->updatePassword());
    }

    /**
     * @return \App\Services\Model\User\Request
     */
    protected function request(): Request
    {
        return new Request($this->request, $this->user);
    }
}
