<?php declare(strict_types=1);

namespace App\Models\Traits;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage as BaseStorage;

trait Storage
{
    /**
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public static function disk(): Filesystem
    {
        return BaseStorage::disk('private');
    }
}
