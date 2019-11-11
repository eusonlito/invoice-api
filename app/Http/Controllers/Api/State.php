<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Services\Model\State\Request;

class State extends ControllerAbstract
{
    /**
     * GET /state/{country_id}
     *
     * @param int $country_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(int $country_id): JsonResponse
    {
        return $this->json($this->request()->indexCached($country_id));
    }

    /**
     * @return \App\Services\Model\State\Request
     */
    protected function request(): Request
    {
        return new Request($this->request, $this->user);
    }
}
