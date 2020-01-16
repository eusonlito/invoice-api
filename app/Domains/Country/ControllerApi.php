<?php declare(strict_types=1);

namespace App\Domains\Country;

use Illuminate\Http\JsonResponse;
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
     * GET /country
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->json($this->cache(__METHOD__, function () {
            return $this->fractal('simple', $this->request()->index());
        }));
    }
}
