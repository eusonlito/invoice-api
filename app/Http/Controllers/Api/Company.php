<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Domains\Company\Request;

class Company extends ControllerAbstract
{
    /**
     * @const
     */
    protected const REQUEST = Request::class;

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
    public function create(): JsonResponse
    {
        return $this->json($this->request()->create());
    }

    /**
     * PATCH /company
     *
     * @uses POST array
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(): JsonResponse
    {
        return $this->json($this->request()->update());
    }
}
