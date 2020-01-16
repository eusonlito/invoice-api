<?php declare(strict_types=1);

namespace App\Domains\Invoice;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
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
    protected const REQUEST = Request::class;

    /**
     * GET /invoice
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->json($this->cache(__METHOD__, function () {
            return $this->fractal('simple', $this->request()->index());
        }));
    }

    /**
     * GET /invoice/export
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(): JsonResponse
    {
        return $this->json($this->cache(__METHOD__, function () {
            return $this->fractal('export', $this->request()->export());
        }));
    }

    /**
     * GET /invoice/export/{format}/{filter}
     *
     * @return \Illuminate\Http\Response
     */
    public function exportFormatFilter(string $format, string $filter): Response
    {
        if ($format === 'zip') {
            $response = $this->request()->exportFormatFilter($format, $filter);
        } elseif ($format === 'csv') {
            $response = $this->cache(__METHOD__, function () use ($format, $filter) {
                return $this->request()->exportFormatFilter($format, $filter);
            });
        } else {
            $response = $this->cache(__METHOD__, function () use ($format, $filter) {
                return $this->fractal('detail', $this->request()->exportFormatFilter($format, $filter));
            });
        }

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
        return $this->json($this->cache(__METHOD__, function () use ($id) {
            return $this->fractal('detail', $this->request()->detail($id));
        }));
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
        return $this->json($this->fractal('detail', $this->request()->create()));
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
        return $this->json($this->fractal('detail', $this->request()->update($id)));
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
        return $this->json($this->fractal('detail', $this->request()->paid($id)));
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
        return $this->json($this->fractal('detail', $this->request()->duplicate($id)));
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
        $this->request()->delete($id);
    }

    /**
     * GET /invoice/w
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function wIndex(): JsonResponse
    {
        return $this->json($this->cache(__METHOD__, function () {
            return [
                'invoice' => $this->fractal('simple', $this->request()->index()),
                'invoice_recurring' => $this->fractalFrom('InvoiceRecurring', 'simple', $this->requestFrom('InvoiceRecurring')->index()),
                'invoice_serie' => $this->fractalFrom('InvoiceSerie', 'simple', $this->requestFrom('InvoiceSerie')->index()),
                'invoice_status' => $this->fractalFrom('InvoiceStatus', 'simple', $this->requestFrom('InvoiceStatus')->index()),
                'payment' => $this->fractalFrom('Payment', 'simple', $this->requestFrom('Payment')->index()),
            ];
        }));
    }

    /**
     * GET /invoice/w/create
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function wCreate(): JsonResponse
    {
        return $this->json($this->cache(__METHOD__, function () {
            return $this->w();
        }));
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
        return $this->json($this->cache(__METHOD__, function () use ($id) {
            return $this->w() + [
                'invoice' => $this->fractal('detail', $this->request()->detail($id))
            ];
        }));
    }

    /**
     * @return array
     */
    protected function w(): array
    {
        return [
            'client_address' => $this->fractalFrom('ClientAddress', 'simple', $this->requestFrom('ClientAddress')->enabled()),
            'discount' => $this->fractalFrom('Discount', 'simple', $this->requestFrom('Discount')->enabled()),
            'invoice_recurring' => $this->fractalFrom('InvoiceRecurring', 'simple', $this->requestFrom('InvoiceRecurring')->enabled()),
            'invoice_serie' => $this->fractalFrom('InvoiceSerie', 'simple', $this->requestFrom('InvoiceSerie')->enabled()),
            'invoice_status' => $this->fractalFrom('InvoiceStatus', 'simple', $this->requestFrom('InvoiceStatus')->enabled()),
            'payment' => $this->fractalFrom('Payment', 'simple', $this->requestFrom('Payment')->enabled()),
            'product' => $this->fractalFrom('Product', 'simple', $this->requestFrom('Product')->enabled()),
            'shipping' => $this->fractalFrom('Shipping', 'simple', $this->requestFrom('Shipping')->enabled()),
            'tax' => $this->fractalFrom('Tax', 'simple', $this->requestFrom('Tax')->enabled()),
        ];
    }
}
