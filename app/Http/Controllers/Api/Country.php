<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Services\Model\Country\Request;

class Country extends ControllerAbstract
{
    /**
     * GET /country
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->json($this->request()->indexCached());
    }

    /**
     * @return \App\Services\Model\Country\Request
     */
    protected function request(): Request
    {
        return new Request($this->request, $this->user);
    }
}
