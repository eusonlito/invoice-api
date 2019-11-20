<?php declare(strict_types=1);

namespace App\Domain\User;

use App\Models\User as Model;
use App\Domain\FractalAbstract;
use App\Domain\Language\Fractal as Language;

class Fractal extends FractalAbstract
{
    /**
     * @param \App\Models\User $row
     *
     * @return array
     */
    public static function simple(Model $row): array
    {
        return [
            'id' => $row->id,
            'user' => $row->user,
            'name' => $row->name,
            'language' => static::relationIfLoaded($row, 'language', Language::class),
        ];
    }

    /**
     * @param \App\Models\User $row
     *
     * @return array
     */
    public static function detail(Model $row): array
    {
        return [
            'confirmed_at' => $row->confirmed_at
        ] + static::simple($row);
    }
}
