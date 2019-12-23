<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Domains\InvoiceRecurring\Request;

class InvoiceRecurring extends ControllerAbstract
{
    /**
     * @const
     */
    protected const REQUEST = Request::class;

    /**
     * GET /invoice-recurring
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->json($this->request()->indexCached());
    }

    /**
     * GET /invoice-recurring/enabled
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function enabled(): JsonResponse
    {
        return $this->json($this->request()->enabledCached());
    }

    /**
     * GET /invoice-recurring/export
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(): JsonResponse
    {
        return $this->json($this->request()->exportCached());
    }

    /**
     * GET /invoice-recurring/{id}
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
     * POST /invoice-recurring
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
     * PATCH /invoice-recurring/{id}
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
     * DELETE /invoice-recurring/{id}
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
