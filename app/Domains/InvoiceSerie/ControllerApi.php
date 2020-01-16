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
    protected const REQUEST = Request::class;

    /**
     * GET /invoice-serie
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
     * GET /invoice-serie/enabled
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function enabled(): JsonResponse
    {
        return $this->json($this->cache(__METHOD__, function () {
            return $this->fractal('simple', $this->request()->enabled());
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
            return $this->fractal('export', $this->request()->export());
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
            return $this->fractal('detail', $this->request()->detail($id));
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
        return response($this->request()->css($id));
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
        return $this->json($this->fractal('detail', $this->request()->create()));
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
        return $this->json($this->fractal('detail', $this->request()->update($id)));
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
        return response($this->request()->cssPreview($id), 200, [
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
        return response($this->request()->cssUpdate($id));
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
        $this->request()->delete($id);
    }
}
