<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
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
     * PATCH /invoice-configuration
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
     * GET /invoice-configuration/css
     *
     * @return \Illuminate\Http\Response
     */
    public function css(): Response
    {
        return response($this->request()->cssCached());
    }

    /**
     * POST /invoice-configuration/css/preview
     *
     * @uses POST string $css
     *
     * @return \Illuminate\Http\Response
     */
    public function cssPreview(): Response
    {
        return response($this->request()->cssPreview(), 200, [
            'Content-Type' => 'application/pdf'
        ]);
    }

    /**
     * PATCH /invoice-configuration/css
     *
     * @uses POST array
     *
     * @return \Illuminate\Http\Response
     */
    public function cssUpdate(): Response
    {
        return response($this->request()->cssUpdate());
    }

    /**
     * @return \App\Services\Model\InvoiceConfiguration\Request
     */
    protected function request(): Request
    {
        return new Request($this->request, $this->user);
    }
}
