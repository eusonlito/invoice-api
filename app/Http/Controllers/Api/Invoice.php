<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Services\Model\Invoice\Request;

class Invoice extends ControllerAbstract
{
    /**
     * GET /invoice
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->json($this->request()->indexCached());
    }

    /**
     * GET /invoice/export
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(): JsonResponse
    {
        return $this->json($this->request()->exportCached());
    }

    /**
     * GET /invoice/preview
     *
     * @return \Illuminate\Http\Response
     */
    public function preview(): Response
    {
        return response($this->request()->preview());
    }

    /**
     * GET /invoice/{id}
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
     * GET /invoice/{id}/preview
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function detailPreview(int $id): Response
    {
        return $this->json($this->request()->detailPreviewCached($id));
    }

    /**
     * POST /invoice
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
     * PATCH /invoice/{id}
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
     * @return \App\Services\Model\Invoice\Request
     */
    protected function request(): Request
    {
        return new Request($this->request, $this->user);
    }
}
