<?php declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\ModelAbstract;

trait Demo
{
    /**
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(static function(ModelAbstract $model): bool {
            return false;
        });

        static::creating(static function(ModelAbstract $model): bool {
            return false;
        });

        static::saving(static function(ModelAbstract $model): bool {
            return false;
        });

        static::updating(static function(ModelAbstract $model): bool {
            return false;
        });
    }
}
