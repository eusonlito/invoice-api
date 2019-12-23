<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Domains\InvoiceFile\Request;

class InvoiceFile extends ControllerAbstract
{
    /**
     * @const
     */
    protected const REQUEST = Request::class;

    /**
     * GET /invoice-file/{id}
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
     * GET /invoice-file/invoice/{invoice_id}
     *
     * @param int $invoice_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function invoice(int $invoice_id): JsonResponse
    {
        return $this->json($this->request()->invoiceCached($invoice_id));
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
        $row = $this->request()->invoiceMain($invoice_id);

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
        $row = $this->request()->download($id);

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
        return $this->json($this->request()->invoiceCreate($invoice_id));
    }

    /**
     * DELETE /invoice-file/{id}
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        return $this->json($this->request()->delete($id));
    }
}
