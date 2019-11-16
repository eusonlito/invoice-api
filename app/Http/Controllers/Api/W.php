<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Services\Model;

class W extends ControllerAbstract
{
    /**
     * GET /w/invoice
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function invoiceCreate(): JsonResponse
    {
        return $this->json($this->invoice());
    }

    /**
     * GET /w/invoice/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function invoiceUpdate(int $id): JsonResponse
    {
        return $this->json($this->invoice() + [
            'invoice' => (new Model\Invoice\Request($this->request, $this->user))->detailCached($id)
        ]);
    }

    /**
     * @return array
     */
    protected function invoice(): array
    {
        return [
            'client_address' => (new Model\ClientAddress\Request($this->request, $this->user))->enabledCached(),
            'discount' => (new Model\Discount\Request($this->request, $this->user))->enabledCached(),
            'invoice_serie' => (new Model\InvoiceSerie\Request($this->request, $this->user))->enabledCached(),
            'invoice_status' => (new Model\InvoiceStatus\Request($this->request, $this->user))->enabledCached(),
            'payment' => (new Model\Payment\Request($this->request, $this->user))->enabledCached(),
            'product' => (new Model\Product\Request($this->request, $this->user))->enabledCached(),
            'shipping' => (new Model\Shipping\Request($this->request, $this->user))->enabledCached(),
            'tax' => (new Model\Tax\Request($this->request, $this->user))->enabledCached()
        ];
    }
}
