<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Domains\Configuration\Request;

class Configuration extends ControllerAbstract
{
    /**
     * GET /configuration
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->json($this->request()->indexCached());
    }

    /**
     * GET /configuration/cache/version
     *
     * @return \Illuminate\Http\Response
     */
    public function cacheVersion(): Response
    {
        return response($this->request()->cacheVersion());
    }

    /**
     * @return \App\Domains\Configuration\Request
     */
    protected function request(): Request
    {
        return new Request($this->request, $this->user);
    }
}
