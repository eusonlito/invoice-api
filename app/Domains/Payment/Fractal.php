<?php declare(strict_types=1);

namespace App\Domains\Payment;

use App\Models\Payment as Model;
use App\Services\Response\FractalAbstract;

class Fractal extends FractalAbstract
{
    /**
     * @param \App\Models\Payment $row
     *
     * @return array
     */
    public static function simple(Model $row): array
    {
        return [
            'id' => $row->id,
            'name' => $row->name,
            'description' => $row->description,
            'default' => $row->default,
            'enabled' => $row->enabled,
        ];
    }

    /**
     * @param \App\Models\Payment $row
     *
     * @return array
     */
    public static function detail(Model $row): array
    {
        return static::simple($row);
    }

    /**
     * @param \App\Models\Payment $row
     *
     * @return array
     */
    public static function export(Model $row): array
    {
        return static::simple($row);
    }
}
