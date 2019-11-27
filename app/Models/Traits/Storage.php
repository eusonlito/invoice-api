<?php declare(strict_types=1);

namespace App\Models\Traits;

use Illuminate\Contracts\Filesystem\Filesystem;

trait Storage
{
    /**
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public static function disk(): Filesystem
    {
        return service()->disk('private');
    }
}
