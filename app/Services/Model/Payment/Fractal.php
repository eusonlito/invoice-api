<?php declare(strict_types=1);

namespace App\Services\Model\Payment;

use App\Models\Payment as Model;
use App\Services\Model\FractalAbstract;

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
            'enabled' => $row->enabled,
            'description' => $row->description,
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
}
