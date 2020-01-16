<?php declare(strict_types=1);

namespace App\Domains\InvoiceFile;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
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
     * GET /invoice-file/{id}
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
     * GET /invoice-file/invoice/{invoice_id}
     *
     * @param int $invoice_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function invoice(int $invoice_id): JsonResponse
    {
        return $this->json($this->cache(__METHOD__, function () use ($invoice_id) {
            return $this->fractal('detail', $this->repository()->invoice($invoice_id));
        }));
    }

    /**
     * GET /invoice-file/invoice/{invoice_id}/main
     *
     * @param int $invoice_id
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function main(int $invoice_id): BinaryFileResponse
    {
        $row = $this->repository()->invoiceMain($invoice_id);

        return response()->download($row->file_absolute, $row->name);
    }

    /**
     * GET /invoice-file/{id}/download
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(int $id): BinaryFileResponse
    {
        $row = $this->repository()->download($id);

        return response()->download($row->file_absolute, $row->name);
    }

    /**
     * POST /invoice-file/invoice/{invoice_id}
     *
     * @param int $invoice_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(int $invoice_id): JsonResponse
    {
        return $this->json($this->fractal('detail', $this->repository()->invoiceCreate($invoice_id)));
    }

    /**
     * DELETE /invoice-file/{id}
     *
     * @return void
     */
    public function delete(int $id): void
    {
        $this->repository()->delete($id);
    }
}
