<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Domains;
use App\Domains\Client\Request;

class Client extends ControllerAbstract
{
    /**
     * GET /client
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->json($this->request()->indexCached());
    }

    /**
     * GET /client/export
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(): JsonResponse
    {
        return $this->json($this->request()->exportCached());
    }

    /**
     * GET /client/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(int $id): JsonResponse
    {
        return $this->json($this->request()->detailCached($id));
    }

    /**
     * POST /client
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
     * PATCH /client/{id}
     *
     * @param int $id
     *
     * @uses POST array
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(int $id): JsonResponse
    {
        return $this->json($this->request()->update($id));
    }

    /**
     * DELETE /client/{id}
     *
     * @param int $id
     *
     * @return void
     */
    public function delete(int $id): void
    {
        $this->json($this->request()->delete($id));
    }

    /**
     * GET /client/w
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function wCreate(): JsonResponse
    {
        return $this->json($this->w());
    }

    /**
     * GET /client/w/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function wUpdate(int $id): JsonResponse
    {
        return $this->json($this->w() + [
            'client' => $this->request()->detailCached($id)
        ]);
    }

    /**
     * @return array
     */
    protected function w(): array
    {
        return [
            'discount' => (new Domains\Discount\Request($this->request, $this->user))->enabledCached(),
            'payment' => (new Domains\Payment\Request($this->request, $this->user))->enabledCached(),
            'shipping' => (new Domains\Shipping\Request($this->request, $this->user))->enabledCached(),
            'tax' => (new Domains\Tax\Request($this->request, $this->user))->enabledCached()
        ];
    }

    /**
     * @return \App\Domains\Client\Request
     */
    protected function request(): Request
    {
        return new Request($this->request, $this->user);
    }
}
