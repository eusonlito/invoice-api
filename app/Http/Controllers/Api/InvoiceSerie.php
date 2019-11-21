<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Domains\InvoiceSerie\Request;

class InvoiceSerie extends ControllerAbstract
{
    /**
     * GET /invoice-serie
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->json($this->request()->indexCached());
    }

    /**
     * GET /invoice-serie/enabled
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function enabled(): JsonResponse
    {
        return $this->json($this->request()->enabledCached());
    }

    /**
     * GET /invoice-serie/export
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(): JsonResponse
    {
        return $this->json($this->request()->exportCached());
    }

    /**
     * GET /invoice-serie/{id}
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
     * GET /invoice-serie/{id}/css
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function css(int $id): Response
    {
        return response($this->request()->css($id));
    }

    /**
     * POST /invoice-serie
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
     * PATCH /invoice-serie/{id}
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
     * POST /invoice-serie/{id}/css
     *
     * @param int $id
     *
     * @uses POST array
     *
     * @return \Illuminate\Http\Response
     */
    public function cssPreview(int $id): Response
    {
        return response($this->request()->cssPreview($id), 200, [
            'Content-Type' => 'application/pdf'
        ]);
    }

    /**
     * PATCH /invoice-serie/{id}/css
     *
     * @param int $id
     *
     * @uses POST array
     *
     * @return \Illuminate\Http\Response
     */
    public function cssUpdate(int $id): Response
    {
        return response($this->request()->cssUpdate($id));
    }

    /**
     * DELETE /invoice-serie/{id}
     *
     * @param int $id
     *
     * @return void
     */
    public function delete(int $id): void
    {
        $this->json($this->request()->delete($id));
    }

    /**
     * @return \App\Domains\InvoiceSerie\Request
     */
    protected function request(): Request
    {
        return new Request($this->request, $this->user);
    }
}
