<?php declare(strict_types=1);

namespace App\Domains\Configuration;

use Illuminate\Database\Eloquent\Builder;
use App\Domains\RequestAbstract;
use App\Models\Configuration as Model;

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
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function model(): Builder
    {
        return Model::where('public', 1);
    }
}
