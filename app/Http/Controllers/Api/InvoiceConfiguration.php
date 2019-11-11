<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Services\Model\InvoiceConfiguration\Request;

class InvoiceConfiguration extends ControllerAbstract
{
    /**
     * GET /invoice-configuration
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->json($this->request()->indexCached());
    }

    /**
     * POST /invoice-configuration
     *
     * @uses POST array
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(): JsonResponse
    {
        return $this->json($this->request()->update());
    }

    /**
     * @return \App\Services\Model\InvoiceConfiguration\Request
     */
    protected function request(): Request
    {
        return new Request($this->request, $this->user);
    }
}
