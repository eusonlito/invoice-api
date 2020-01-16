<?php declare(strict_types=1);

namespace App\Domains\Configuration;

use Illuminate\Http\JsonResponse;
use App\Domains\ControllerApiAbstract;

class ControllerApi extends ControllerApiAbstract
{
    /**
     * @const string
     */
    protected const REPOSITORY = Repository::class;

    /**
     * GET /configuration
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->json($this->cache(__METHOD__, function () {
            return $this->repository()->index();
        }));
    }
}
