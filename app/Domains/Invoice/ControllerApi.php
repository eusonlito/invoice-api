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
    protected const REPOSITORY = Repository::class;

    /**
     * GET /invoice
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
     * GET /invoice/export
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
     * GET /invoice/export/{format}/{filter}
     *
     * @return \Illuminate\Http\Response
     */
    public function exportFormatFilter(string $format, string $filter): Response
    {
        switch ($format) {
            case 'zip':
                $response = $this->exportFormatFilterZip($filter);
                break;

            case 'csv':
                $response = $this->exportFormatFilterCsv($filter);
                break;

            default:
                $response = $this->exportFormatFilterJson($filter);
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
     * @param string $filter
     *
     * @return string
     */
    protected function exportFormatFilterZip(string $filter): string
    {
        return $this->repository()->exportFormatFilter('zip', $filter);
    }

    /**
     * @param string $filter
     *
     * @return string
     */
    protected function exportFormatFilterCsv(string $filter): string
    {
        return $this->cache(__METHOD__, function () use ($filter) {
            return $this->repository()->exportFormatFilter('csv', $filter);
        });
    }

    /**
     * @param string $filter
     *
     * @return array
     */
    protected function exportFormatFilterJson(string $filter): array
    {
        return $this->cache(__METHOD__, function () use ($filter) {
            return $this->fractal('detail', $this->repository()->exportFormatFilter('json', $filter));
        });
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
            return $this->fractal('detail', $this->repository()->detail($id));
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
        return $this->json($this->fractal('detail', $this->repository()->create()));
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
        return $this->json($this->fractal('detail', $this->repository()->update($id)));
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
        return $this->json($this->fractal('detail', $this->repository()->paid($id)));
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
        return $this->json($this->fractal('detail', $this->repository()->duplicate($id)));
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
        $this->repository()->delete($id);
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
                'invoice' => $this->fractal('simple', $this->repository()->index()),
                'invoice_recurring' => $this->fractalFrom('InvoiceRecurring', 'simple', $this->repositoryFrom('InvoiceRecurring')->index()),
                'invoice_serie' => $this->fractalFrom('InvoiceSerie', 'simple', $this->repositoryFrom('InvoiceSerie')->index()),
                'invoice_status' => $this->fractalFrom('InvoiceStatus', 'simple', $this->repositoryFrom('InvoiceStatus')->index()),
                'payment' => $this->fractalFrom('Payment', 'simple', $this->repositoryFrom('Payment')->index()),
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
                'invoice' => $this->fractal('detail', $this->repository()->detail($id))
            ];
        }));
    }

    /**
     * @return array
     */
    protected function w(): array
    {
        return [
            'client_address' => $this->fractalFrom('ClientAddress', 'simple', $this->repositoryFrom('ClientAddress')->enabled()),
            'discount' => $this->fractalFrom('Discount', 'simple', $this->repositoryFrom('Discount')->enabled()),
            'invoice_recurring' => $this->fractalFrom('InvoiceRecurring', 'simple', $this->repositoryFrom('InvoiceRecurring')->enabled()),
            'invoice_serie' => $this->fractalFrom('InvoiceSerie', 'simple', $this->repositoryFrom('InvoiceSerie')->enabled()),
            'invoice_status' => $this->fractalFrom('InvoiceStatus', 'simple', $this->repositoryFrom('InvoiceStatus')->enabled()),
            'payment' => $this->fractalFrom('Payment', 'simple', $this->repositoryFrom('Payment')->enabled()),
            'product' => $this->fractalFrom('Product', 'simple', $this->repositoryFrom('Product')->enabled()),
            'shipping' => $this->fractalFrom('Shipping', 'simple', $this->repositoryFrom('Shipping')->enabled()),
            'tax' => $this->fractalFrom('Tax', 'simple', $this->repositoryFrom('Tax')->enabled()),
        ];
    }
}
