<?php declare(strict_types=1);

namespace App\Domains\InvoiceSerie;

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
    protected const REPOSITORY = Repository::class;

    /**
     * GET /invoice-serie
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
     * GET /invoice-serie/enabled
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function enabled(): JsonResponse
    {
        return $this->json($this->cache(__METHOD__, function () {
            return $this->fractal('simple', $this->repository()->enabled());
        }));
    }

    /**
     * GET /invoice-serie/export
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
     * GET /invoice-serie/{id}
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
     * GET /invoice-serie/{id}/css
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function css(int $id): Response
    {
        return response($this->repository()->css($id));
    }

    /**
     * POST /invoice-serie
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
     * PATCH /invoice-serie/{id}
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
     * POST /invoice-serie/{id}/css
     *
     * @param int $id
     *
     * @uses POST array
     *
     * @return \Illuminate\Http\Response
     */
    public function cssPreview(int $id): Response
    {
        return response($this->repository()->cssPreview($id), 200, [
            'Content-Type' => 'application/pdf'
        ]);
    }

    /**
     * PATCH /invoice-serie/{id}/css
     *
     * @param int $id
     *
     * @uses POST array
     *
     * @return \Illuminate\Http\Response
     */
    public function cssUpdate(int $id): Response
    {
        return response($this->repository()->cssUpdate($id));
    }

    /**
     * DELETE /invoice-serie/{id}
     *
     * @param int $id
     *
     * @return void
     */
    public function delete(int $id): void
    {
        $this->repository()->delete($id);
    }
}
