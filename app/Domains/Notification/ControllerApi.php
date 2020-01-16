<?php declare(strict_types=1);

namespace App\Domains\Notification;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Domains\ControllerApiAbstract;

class ControllerApi extends ControllerApiAbstract
{
    /**
     * @const string
     */
    protected const FRACTAL = Fractal::class;

    /**
     * @const string
     */
    protected const REQUEST = Request::class;

    /**
     * GET /notification
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->json($this->cache(__METHOD__, function () {
            return $this->fractal('simple', $this->request()->index());
        }));
    }

    /**
     * GET /notification/count
     *
     * @return \Illuminate\Http\Response
     */
    public function count(): Response
    {
        return response($this->cache(__METHOD__, function () {
            return $this->request()->count();
        }));
    }

    /**
     * GET /notification/last
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function last(): JsonResponse
    {
        return $this->json($this->cache(__METHOD__, function () {
            return $this->fractal('simple', $this->request()->last());
        }));
    }

    /**
     * PATCH /notification
     *
     * @return void
     */
    public function read(): void
    {
        $this->request()->read();
    }
}
