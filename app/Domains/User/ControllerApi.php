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
    protected const REPOSITORY = Repository::class;

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
            'user' => $this->fractal('detail', $this->repository()->signup()),
            'token' => $this->repository()->authToken()
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
        $this->repository()->confirmStart();
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
        return $this->json($this->fractal('detail', $this->repository()->confirmFinish($hash)));
    }

    /**
     * GET /user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(): JsonResponse
    {
        return $this->json($this->cache(__METHOD__, function () {
            return $this->fractal('detail', $this->repository()->detail());
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
            'user' => $this->fractal('detail', $this->repository()->authCredentials()),
            'token' => $this->repository()->authToken()
        ]);
    }

    /**
     * GET /user/auth/refresh
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authRefresh(): JsonResponse
    {
        return $this->json(['token' => $this->repository()->authToken()]);
    }

    /**
     * GET /user/auth/logout
     *
     * @return void
     */
    public function authLogout(): void
    {
        $this->repository()->authLogout();
    }

    /**
     * POST /user/password/reset
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function passwordResetStart(): JsonResponse
    {
        return $this->json($this->fractal('detail', $this->repository()->passwordResetStart()));
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
        return $this->json($this->fractal('detail', $this->repository()->passwordResetFinish($hash)));
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
        return $this->json($this->fractal('detail', $this->repository()->updateProfile()));
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
        return $this->json($this->fractal('detail', $this->repository()->updatePassword()));
    }
}
