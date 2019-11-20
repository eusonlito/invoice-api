<?php declare(strict_types=1);

namespace App\Domain\Language;

use App\Models\Language as Model;
use App\Domain\FractalAbstract;

class Fractal extends FractalAbstract
{
    /**
     * @param \App\Models\Language $row
     *
     * @return array
     */
    public static function simple(Model $row): array
    {
        return [
            'id' => $row->id,
            'iso' => $row->iso,
            'name' => $row->name,
            'default' => $row->default
        ];
    }
}
