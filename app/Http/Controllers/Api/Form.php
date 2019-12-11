<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Domains\Form\Request;

class Form extends ControllerAbstract
{
    /**
     * POST /form/contact
     *
     * @uses POST array
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function contact(): JsonResponse
    {
        return $this->json($this->request()->contact());
    }

    /**
     * @return \App\Domains\Form\Request
     */
    protected function request(): Request
    {
        return new Request($this->request, $this->user);
    }
}
