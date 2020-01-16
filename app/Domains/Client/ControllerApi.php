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
    protected const REPOSITORY = Repository::class;

    /**
     * GET /client
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->json($this->cache(__METHOD__, function () {
            return $this->fractal('simple', $this->repository()->index());
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
            return $this->fractal('export', $this->repository()->export());
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
            return $this->fractal('detail', $this->repository()->detail($id));
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
        return $this->json($this->fractal('detail', $this->repository()->create()));
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
        return $this->json($this->fractal('detail', $this->repository()->update($id)));
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
        $this->repository()->delete($id);
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
                'client' => $this->fractal('detail', $this->repository()->detail($id))
            ];
        }));
    }

    /**
     * @return array
     */
    protected function w(): array
    {
        return [
            'discount' => $this->fractalFrom('Discount', 'detail', $this->repositoryFrom('Discount')->enabled()),
            'payment' => $this->fractalFrom('Payment', 'detail', $this->repositoryFrom('Payment')->enabled()),
            'shipping' => $this->fractalFrom('Shipping', 'detail', $this->repositoryFrom('Shipping')->enabled()),
            'tax' => $this->fractalFrom('Tax', 'detail', $this->repositoryFrom('Tax')->enabled()),
        ];
    }
}
