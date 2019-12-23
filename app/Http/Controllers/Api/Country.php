<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Domains\Country\Request;

class Country extends ControllerAbstract
{
    /**
     * @const
     */
    protected const REQUEST = Request::class;

    /**
     * GET /country
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->json($this->request()->indexCached());
    }
}
