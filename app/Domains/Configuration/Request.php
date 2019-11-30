<?php declare(strict_types=1);

namespace App\Domains\Configuration;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Configuration as Model;
use App\Domains\RequestAbstract;

class Request extends RequestAbstract
{
    /**
     * @return array
     */
    public function index(): array
    {
        return $this->model()->pluck('value', 'key')->toArray();
    }

    /**
     * @return array
     */
    public function indexCached(): array
    {
        return $this->cache(__METHOD__, fn () => $this->index());
    }

    /**
     * @return string
     */
    public function cacheVersion(): string
    {
        return config('cache.version');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function model(): Builder
    {
        return Model::where('public', 1);
    }
}
