<?php declare(strict_types=1);

namespace App\Domains\Form;

use Illuminate\Http\JsonResponse;
use App\Domains\ControllerApiAbstract;

class ControllerApi extends ControllerApiAbstract
{
    /**
     * @const string
     */
    protected const REPOSITORY = Repository::class;

    /**
     * POST /form/contact
     *
     * @uses POST array
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function contact(): JsonResponse
    {
        return $this->json($this->repository()->contact());
    }
}
