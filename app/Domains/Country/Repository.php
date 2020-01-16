<?php declare(strict_types=1);

namespace App\Domains\Country;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\Domains\RepositoryAbstract;
use App\Models\Country as Model;

class Repository extends RepositoryAbstract
{
    /**
     * @const string
     */
    protected const MODEL = Model::class;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function index(): Collection
    {
        return $this->model()->list()->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function model(): Builder
    {
        return Model::enabled();
    }
}
