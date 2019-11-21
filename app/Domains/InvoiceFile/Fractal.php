<?php declare(strict_types=1);

namespace App\Domains\InvoiceFile;

use App\Models\InvoiceFile as Model;
use App\Domains\FractalAbstract;

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

    /**
     * @param \App\Models\InvoiceFile $row
     *
     * @return array
     */
    public static function export(Model $row): array
    {
        return static::simple($row);
    }
}
