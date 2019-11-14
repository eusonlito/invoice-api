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
     * @param array $keys = []
     *
     * @return \Illuminate\Support\Collection
     */
    public function update(array $keys = []): Collection
    {
        $list = collect();

        foreach (($keys ?: $this->keys) as $key) {
            $each = $this->firstOrNew($key);

            $each->user_id = $this->user->id;
            $each->value = $this->value($key, $this->data[$key] ?? null);
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
     * @param ?string $value
     *
     * @return mixed
     */
    protected function value(string $key, ?string $value)
    {
        switch ($key) {
            case 'number_prefix':
                return $this->valueNumberPrefix($value);

            case 'number_fill':
                return $this->valueNumberFill($value);

            case 'number_next':
                return $this->valueNumberNext($value);

            case 'css':
                return $this->valueCss($value);

            default:
                return '';
        }
    }

    /**
     * @param ?string $value
     *
     * @return string
     */
    protected function valueNumberPrefix(?string $value): string
    {
        return (string)$value;
    }

    /**
     * @param ?string $value
     *
     * @return int
     */
    protected function valueNumberFill(?string $value): int
    {
        return (int)$value;
    }

    /**
     * @param ?string $value
     *
     * @return int
     */
    protected function valueNumberNext(?string $value): int
    {
        return (int)$value;
    }

    /**
     * @param ?string $value
     *
     * @return string
     */
    protected function valueCss(?string $value): string
    {
        return StoreCss::save($this->user->company, $value);
    }
}
