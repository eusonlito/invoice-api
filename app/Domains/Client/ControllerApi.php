<?php declare(strict_types=1);

namespace App\Domains\Client;

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
     * GET /client
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
     * GET /client/export
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(): JsonResponse
    {
        return $this->json($this->cache(__METHOD__, function () {
            return $this->fractal('export', $this->request()->export());
        }));
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
        return $this->json($this->cache(__METHOD__, function () use ($id) {
            return $this->fractal('detail', $this->request()->detail($id));
        }));
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
        return $this->json($this->fractal('detail', $this->request()->create()));
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
        return $this->json($this->fractal('detail', $this->request()->update($id)));
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
        $this->request()->delete($id);
    }

    /**
     * GET /client/w
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function wCreate(): JsonResponse
    {
        return $this->json($this->cache(__METHOD__, function () {
            return $this->w();
        }));
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
        return $this->json($this->cache(__METHOD__, function () use ($id) {
            return $this->w() + [
                'client' => $this->fractal('detail', $this->request()->detail($id))
            ];
        }));
    }

    /**
     * @return array
     */
    protected function w(): array
    {
        return [
            'discount' => $this->fractalFrom('Discount', 'detail', $this->requestFrom('Discount')->enabled()),
            'payment' => $this->fractalFrom('Payment', 'detail', $this->requestFrom('Payment')->enabled()),
            'shipping' => $this->fractalFrom('Shipping', 'detail', $this->requestFrom('Shipping')->enabled()),
            'tax' => $this->fractalFrom('Tax', 'detail', $this->requestFrom('Tax')->enabled()),
        ];
    }
}
