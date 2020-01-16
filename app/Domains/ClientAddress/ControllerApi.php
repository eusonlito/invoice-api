<?php declare(strict_types=1);

namespace App\Domains\ClientAddress;

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
     * GET /client-address/enabled
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function enabled(): JsonResponse
    {
        return $this->json($this->cache(__METHOD__, function () {
            return $this->fractal('simple', $this->repository()->enabled());
        }));
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
        return $this->json($this->cache(__METHOD__, function () use ($client_id) {
            return $this->fractal('simple', $this->repository()->client($client_id));
        }));
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
        return $this->json($this->cache(__METHOD__, function () use ($client_id) {
            return $this->fractal('simple', $this->repository()->clientEnabled($client_id));
        }));
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
        return $this->json($this->fractal('detail', $this->repository()->create($client_id)));
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
        return $this->json($this->fractal('detail', $this->repository()->update($id)));
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
        $this->repository()->delete($id);
    }
}
