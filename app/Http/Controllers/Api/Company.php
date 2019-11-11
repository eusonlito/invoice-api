<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Services\Model\Company\Request;

class Company extends ControllerAbstract
{
    /**
     * GET /company
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(): JsonResponse
    {
        return $this->json($this->request()->detailCached());
    }

    /**
     * POST /company
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
     * @return \App\Services\Model\Company\Request
     */
    protected function request(): Request
    {
        return new Request($this->request, $this->user);
    }
}
