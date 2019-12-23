<?php declare(strict_types=1);

namespace App\Domains\Notification;

use App\Models\Notification as Model;
use App\Domains\FractalAbstract;

class Fractal extends FractalAbstract
{
    /**
     * @param \App\Models\Notification $row
     *
     * @return array
     */
    public static function simple(Model $row): array
    {
        return [
            'id' => $row->id,
            'code' => $row->code,
            'title' => $row->title,
            'status' => $row->status,
            'readed_at' => $row->readed_at,
            'created_at' => $row->created_at,
            'invoice_id' => $row->invoice_id,
        ];
    }
}
