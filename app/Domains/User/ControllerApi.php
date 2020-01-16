<?php declare(strict_types=1);

namespace App\Domains\User;

use Illuminate\Http\JsonResponse;
use App\Domains\ControllerApiAbstract;

class ControllerApi extends ControllerApiAbstract
{
    /**
     * @const string
     */
    protected const FRACTAL = Fractal::class;

    /**
     * @const string
     */
    protected const REQUEST = Request::class;

    /**
     * POST /user
     *
     * @uses POST array
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signup(): JsonResponse
    {
        return $this->json([
            'user' => $this->fractal('detail', $this->request()->signup()),
            'token' => $this->request()->authToken()
        ]);
    }

    /**
     * POST /user/confirm
     *
     * @uses POST $user string
     *
     * @return void
     */
    public function confirmStart(): void
    {
        $this->request()->confirmStart();
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
        return $this->json($this->fractal('detail', $this->request()->confirmFinish($hash)));
    }

    /**
     * GET /user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(): JsonResponse
    {
        return $this->json($this->cache(__METHOD__, function () {
            return $this->fractal('detail', $this->request()->detail());
        }));
    }

    /**
     * POST /user/auth
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authCredentials(): JsonResponse
    {
        return $this->json([
            'user' => $this->fractal('detail', $this->request()->authCredentials()),
            'token' => $this->request()->authToken()
        ]);
    }

    /**
     * GET /user/auth/refresh
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authRefresh(): JsonResponse
    {
        return $this->json(['token' => $this->request()->authToken()]);
    }

    /**
     * GET /user/auth/logout
     *
     * @return void
     */
    public function authLogout(): void
    {
        $this->request()->authLogout();
    }

    /**
     * POST /user/password/reset
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function passwordResetStart(): JsonResponse
    {
        return $this->json($this->fractal('detail', $this->request()->passwordResetStart()));
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
        return $this->json($this->fractal('detail', $this->request()->passwordResetFinish($hash)));
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
        return $this->json($this->fractal('detail', $this->request()->updateProfile()));
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
        return $this->json($this->fractal('detail', $this->request()->updatePassword()));
    }
}
