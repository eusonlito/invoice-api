<?php declare(strict_types=1);

namespace App\Services\Model\InvoiceSerie;

use App\Models\InvoiceSerie as Model;
use App\Services\Model\FractalAbstract;

class Fractal extends FractalAbstract
{
    /**
     * @param \App\Models\InvoiceSerie $row
     *
     * @return array
     */
    public static function simple(Model $row): array
    {
        return [
            'id' => $row->id,
            'name' => $row->name,
            'number_prefix' => $row->number_prefix,
            'number_fill' => $row->number_fill,
            'number_next' => $row->number_next,
            'default' => $row->default,
            'enabled' => $row->enabled,
        ];
    }

    /**
     * @param \App\Models\InvoiceSerie $row
     *
     * @return array
     */
    public static function detail(Model $row): array
    {
        return static::simple($row);
    }
}
