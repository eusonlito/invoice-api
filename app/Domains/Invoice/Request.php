<?php declare(strict_types=1);

namespace App\Domains\Invoice;

use Illuminate\Support\Collection;
use App\Domains\RequestAbstract;
use App\Exceptions\UnexpectedValueException;
use App\Models\Invoice as Model;

class Request extends RequestAbstract
{
    /**
     * @const string
     */
    protected const MODEL = Model::class;

    /**
     * @const string
     */
    protected const STORE = Store::class;

    /**
     * @const string
     */
    protected const VALIDATOR = Validator::class;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function index(): Collection
    {
        return $this->model()->list()->filterByInput($this->request->input())->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function export(): Collection
    {
        return $this->model()->export()->get();
    }

    /**
     * @param string $format
     * @param string $filter
     *
     * @return \Illuminate\Support\Collection|string
     */
    public function exportFormatFilter(string $format, string $filter)
    {
        if (in_array($format, ['json', 'csv', 'zip'], true) === false) {
            throw new UnexpectedValueException();
        }

        $model = $this->model();

        if ($filter) {
            $model->filterByInput($this->request->input());
        }

        if ($format === 'csv') {
            return Service\Csv::export($model->exportCsv()->get());
        }

        if ($format === 'zip') {
            return Service\Zip::export($model->exportZip()->get());
        }

        return $model->export()->get();
    }

    /**
     * @param int $id
     *
     * @return \App\Models\Invoice
     */
    public function detail(int $id): Model
    {
        return $this->modelDetailById($id);
    }

    /**
     * @return \App\Models\Invoice
     */
    public function create(): Model
    {
        return $this->store(null, $this->validator('create'))->create();
    }

    /**
     * @param int $id
     *
     * @return \App\Models\Invoice
     */
    public function update(int $id): Model
    {
        return $this->store($this->modelById($id), $this->validator('update'))->update();
    }

    /**
     * @param int $id
     *
     * @return \App\Models\Invoice
     */
    public function paid(int $id): Model
    {
        return $this->store($this->modelById($id))->paid();
    }

    /**
     * @param int $id
     *
     * @return \App\Models\Invoice
     */
    public function duplicate(int $id): Model
    {
        return $this->store($this->modelById($id), $this->validator('duplicate'))->duplicate();
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function delete(int $id): void
    {
        $this->store($this->modelById($id))->delete();
    }
}
