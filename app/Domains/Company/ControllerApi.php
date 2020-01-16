<?php declare(strict_types=1);

namespace App\Domains\Company;

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
    protected const REPOSITORY = Repository::class;

    /**
     * GET /company
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(): JsonResponse
    {
        return $this->json($this->cache(__METHOD__, function () {
            return $this->fractal('detail', $this->repository()->detail());
        }));
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
        return $this->json($this->fractal('detail', $this->repository()->create()));
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
        return $this->json($this->fractal('detail', $this->repository()->update()));
    }
}
