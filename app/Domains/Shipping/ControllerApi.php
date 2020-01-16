<?php declare(strict_types=1);

namespace App\Domains\Shipping;

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
     * GET /shipping
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->json($this->cache(__METHOD__, function () {
            return $this->fractal('simple', $this->repository()->index());
        }));
    }

    /**
     * GET /shipping/enabled
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
     * GET /shipping/export
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(): JsonResponse
    {
        return $this->json($this->cache(__METHOD__, function () {
            return $this->fractal('export', $this->repository()->export());
        }));
    }

    /**
     * GET /shipping/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(int $id): JsonResponse
    {
        return $this->json($this->cache(__METHOD__, function () use ($id) {
            return $this->fractal('detail', $this->repository()->detail($id));
        }));
    }

    /**
     * POST /shipping
     *
     * @uses POST array
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(): JsonResponse
    {
        return $this->json($this->fractal('detail', $this->repository()->create()));
    }

    /**
     * PATCH /shipping/{id}
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
     * DELETE /shipping/{id}
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
