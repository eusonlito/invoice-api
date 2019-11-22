<?php declare(strict_types=1);

namespace App\Services\Logger;

use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class Database
{
    /**
     * @var string
     */
    protected static string $file = '';

    /**
     * @return void
     */
    public static function listen()
    {
        if (static::$file) {
            return;
        }

        static::load();
        static::write('['.date('Y-m-d H:i:s').'] ['.Request::method().'] '.Request::fullUrl());

        DB::listen(static function ($sql) {
            foreach ($sql->bindings as $i => $binding) {
                if ($binding instanceof DateTime) {
                    $sql->bindings[$i] = $binding->format('Y-m-d H:i:s');
                } elseif (is_string($binding)) {
                    $sql->bindings[$i] = "'${binding}'";
                } elseif (is_bool($binding)) {
                    $sql->bindings[$i] = $binding ? 'true' : 'false';
                }
            }

            static::write(vsprintf(str_replace(['%', '?'], ['%%', '%s'], $sql->sql), $sql->bindings));
        });
    }

    /**
     * @return void
     */
    protected static function load()
    {
        static::$file = preg_replace('/[^a-z0-9]+/i', '-', Request::path()).'.log';
        static::$file = storage_path('logs/query/'.date('Y-m-d').'/'.static::$file);

        if (is_dir($dir = dirname(static::$file)) === false) {
            mkdir($dir, 0755, true);
        }
    }

    /**
     * @param string $message
     *
     * @return void
     */
    protected static function write(string $message)
    {
        file_put_contents(static::$file, "\n\n".$message, FILE_APPEND | LOCK_EX);
    }
}
