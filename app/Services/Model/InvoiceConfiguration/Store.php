<?php declare(strict_types=1);

namespace App\Services\Model\InvoiceConfiguration;

use Illuminate\Support\Collection;
use App\Models;
use App\Models\InvoiceConfiguration as Model;
use App\Services\Model\StoreAbstract;

class Store extends StoreAbstract
{
    /**
     * @var array
     */
    protected array $keys = ['number_prefix', 'number_fill', 'number_next'];

    /**
     * @return \Illuminate\Support\Collection
     */
    public function update(): Collection
    {
        $list = collect();

        foreach ($this->keys as $key) {
            $each = $this->firstOrNew($key);

            $each->user_id = $this->user->id;
            $each->value = $this->value($key, $this->data[$key] ?? '');
            $each->public = 1;

            $each->save();

            $list->put($key, $each->value);
        }

        $this->cacheFlush('InvoiceConfiguration');

        return $list;
    }

    /**
     * @param string $key
     *
     * @return \App\Models\InvoiceConfiguration
     */
    public function firstOrNew(string $key): Model
    {
        return Model::firstOrNew([
            'key' => $key,
            'company_id' => $this->user->company_id,
        ]);
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return mixed
     */
    protected function value(string $key, $value)
    {
        switch ($key) {
            case 'number_prefix':
                return $this->valueNumberPrefix($value);

            case 'number_fill':
                return $this->valueNumberFill($value);

            case 'number_next':
                return $this->valueNumberNext($value);

            default:
                return '';
        }
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    protected function valueNumberPrefix($value): string
    {
        return (string)$value;
    }

    /**
     * @param mixed $value
     *
     * @return int
     */
    protected function valueNumberFill($value): int
    {
        return (int)$value;
    }

    /**
     * @param mixed $value
     *
     * @return int
     */
    protected function valueNumberNext($value): int
    {
        return (int)$value;
    }
}
