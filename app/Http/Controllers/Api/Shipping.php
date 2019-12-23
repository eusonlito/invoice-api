<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Domains\Shipping\Request;

class Shipping extends ControllerAbstract
{
    /**
     * @const
     */
    protected const REQUEST = Request::class;

    /**
     * GET /shipping
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->json($this->request()->indexCached());
    }

    /**
     * GET /shipping/enabled
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function enabled(): JsonResponse
    {
        return $this->json($this->request()->enabledCached());
    }

    /**
     * GET /shipping/export
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(): JsonResponse
    {
        return $this->json($this->request()->exportCached());
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
        return $this->json($this->request()->detailCached($id));
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
        return $this->json($this->request()->create());
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
        return $this->json($this->request()->update($id));
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
        $this->json($this->request()->delete($id));
    }
}
