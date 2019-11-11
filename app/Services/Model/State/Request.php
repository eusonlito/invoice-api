<?php declare(strict_types=1);

namespace App\Services\Model\State;

use Illuminate\Database\Eloquent\Builder;
use App\Models\State as Model;
use App\Services\Model\RequestAbstract;

class Request extends RequestAbstract
{
    /**
     * @param int $country_id
     *
     * @return array
     */
    public function index(int $country_id): array
    {
        return $this->fractal('simple', $this->model()->list($country_id)->get());
    }

    /**
     * @param int $country_id
     *
     * @return array
     */
    public function indexCached(int $country_id): array
    {
        return $this->cache(__METHOD__, fn() => $this->index($country_id));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function model(): Builder
    {
        return Model::query();
    }

    /**
     * @param string $name
     * @param mixed $data
     *
     * @return ?array
     */
    protected function fractal(string $name, $data): ?array
    {
        return Fractal::transform($name, $data);
    }
}
