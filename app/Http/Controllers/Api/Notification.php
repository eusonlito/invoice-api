<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Domains\Notification\Request;

class Notification extends ControllerAbstract
{
    /**
     * @const
     */
    protected const REQUEST = Request::class;

    /**
     * GET /notification
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->json($this->request()->indexCached());
    }

    /**
     * GET /notification/count
     *
     * @return \Illuminate\Http\Response
     */
    public function count(): Response
    {
        return response($this->request()->countCached());
    }

    /**
     * GET /notification/last
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function last(): JsonResponse
    {
        return $this->json($this->request()->lastCached());
    }

    /**
     * PATCH /notification
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function read(): JsonResponse
    {
        return $this->json($this->request()->read());
    }
}
