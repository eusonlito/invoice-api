<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Domains\ClientAddress\Request;

class ClientAddress extends ControllerAbstract
{
    /**
     * @const
     */
    protected const REQUEST = Request::class;

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
    public function create(int $client_id): JsonResponse
    {
        return $this->json($this->request()->create($client_id));
    }

    /**
     * POST /client-address/{id}
     *
     * @param int $id
     *
     * @uses POST array
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(int $id): JsonResponse
    {
        return $this->json($this->request()->update($id));
    }

    /**
     * DELETE /client-address/{id}
     *
     * @param int $id
     *
     * @return void
     */
    public function delete(int $id): void
    {
        $this->json($this->request()->delete($id));
    }
}
