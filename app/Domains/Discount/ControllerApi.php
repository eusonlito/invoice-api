<?php declare(strict_types=1);

namespace App\Domains\Discount;

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
     * GET /discount
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
     * GET /discount/enabled
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
     * GET /discount/export
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
     * GET /discount/{id}
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
     * POST /discount
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
     * PATCH /discount/{id}
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
     * DELETE /discount/{id}
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
