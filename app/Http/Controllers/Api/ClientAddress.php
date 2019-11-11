<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Services\Model\ClientAddress\Request;

class ClientAddress extends ControllerAbstract
{
    /**
     * GET /client-address/enabled
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function enabled(): JsonResponse
    {
        return $this->json($this->request()->enabledCached());
    }

    /**
     * GET /client-address/{client_id}
     *
     * @param int $client_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function client(int $client_id): JsonResponse
    {
        return $this->json($this->request()->clientCached($client_id));
    }

    /**
     * GET /client-address/{client_id}/enabled
     *
     * @param int $client_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clientEnabled(int $client_id): JsonResponse
    {
        return $this->json($this->request()->clientEnabledCached($client_id));
    }

    /**
     * POST /client-address/{client_id}
     *
     * @param int $client_id
     *
     * @uses POST
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clientCreate(int $client_id): JsonResponse
    {
        return $this->json($this->request()->clientCreate($client_id));
    }

    /**
     * POST /client-address/{client_id}/{id}
     *
     * @param int $client_id
     * @param int $id
     *
     * @uses POST array
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clientUpdate(int $client_id, int $id): JsonResponse
    {
        return $this->json($this->request()->clientUpdate($client_id, $id));
    }

    /**
     * @return \App\Services\Model\ClientAddress\Request
     */
    protected function request(): Request
    {
        return new Request($this->request, $this->user);
    }
}
