<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Domains;
use App\Domains\Invoice\Request;

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
     * GET /invoice/export/{format}/{filter}
     *
     * @return \Illuminate\Http\Response
     */
    public function exportFormatFilter(string $format, string $filter): Response
    {
        $response = $this->request()->exportFormatFilterCached($format, $filter);

        if (is_array($response)) {
            $response = json_encode($response, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        return response($response, 200, [
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => 'attachment; filename="invoice-export.'.$format.'"'
        ]);
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
     * PATCH /invoice/{id}/paid
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function paid(int $id): JsonResponse
    {
        return $this->json($this->request()->paid($id));
    }

    /**
     * POST /invoice/{id}
     *
     * @uses POST array
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function duplicate(int $id): JsonResponse
    {
        return $this->json($this->request()->duplicate($id));
    }

    /**
     * DELETE /invoice/{id}
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
     * GET /invoice/w
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function wIndex(): JsonResponse
    {
        return $this->json([
            'invoice' => $this->request()->indexCached(),
            'invoice_recurring' => (new Domains\InvoiceRecurring\Request($this->request, $this->user))->indexCached(),
            'invoice_serie' => (new Domains\InvoiceSerie\Request($this->request, $this->user))->indexCached(),
            'invoice_status' => (new Domains\InvoiceStatus\Request($this->request, $this->user))->indexCached(),
            'payment' => (new Domains\Payment\Request($this->request, $this->user))->indexCached(),
        ]);
    }

    /**
     * GET /invoice/w/create
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function wCreate(): JsonResponse
    {
        return $this->json($this->w());
    }

    /**
     * GET /invoice/w/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function wUpdate(int $id): JsonResponse
    {
        return $this->json($this->w() + [
            'invoice' => $this->request()->detailCached($id)
        ]);
    }

    /**
     * @return array
     */
    protected function w(): array
    {
        return [
            'client_address' => (new Domains\ClientAddress\Request($this->request, $this->user))->enabledCached(),
            'discount' => (new Domains\Discount\Request($this->request, $this->user))->enabledCached(),
            'invoice_recurring' => (new Domains\InvoiceRecurring\Request($this->request, $this->user))->enabledCached(),
            'invoice_serie' => (new Domains\InvoiceSerie\Request($this->request, $this->user))->enabledCached(),
            'invoice_status' => (new Domains\InvoiceStatus\Request($this->request, $this->user))->enabledCached(),
            'payment' => (new Domains\Payment\Request($this->request, $this->user))->enabledCached(),
            'product' => (new Domains\Product\Request($this->request, $this->user))->enabledCached(),
            'shipping' => (new Domains\Shipping\Request($this->request, $this->user))->enabledCached(),
            'tax' => (new Domains\Tax\Request($this->request, $this->user))->enabledCached()
        ];
    }

    /**
     * @return \App\Domains\Invoice\Request
     */
    protected function request(): Request
    {
        return new Request($this->request, $this->user);
    }
}
