<?php declare(strict_types=1);

namespace App\Services\Model\InvoiceFile;

use App\Models\InvoiceFile as Model;
use App\Services\Model\FractalAbstract;

class Fractal extends FractalAbstract
{
    /**
     * @param \App\Models\InvoiceFile $row
     *
     * @return array
     */
    public static function simple(Model $row): array
    {
        return [
            'id' => $row->id,
            'name' => $row->name,
            'main' => $row->main
        ];
    }

    /**
     * @param \App\Models\InvoiceFile $row
     *
     * @return array
     */
    public static function detail(Model $row): array
    {
        return static::simple($row);
    }
}
