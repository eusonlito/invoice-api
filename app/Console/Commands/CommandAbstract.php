<?php declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

abstract class CommandAbstract extends Command
{
    /**
     * @param mixed $string
     * @param int|null|string $verbosity = false
     *
     * @return void
     */
    public function info($string, $verbosity = false)
    {
        if (is_array($string)) {
            $string = print_r($string, true);
        }

        parent::info('['.date('Y-m-d H:i:s').'] '.$string, $verbosity);
    }

    /**
     * @param mixed $string
     * @param int|null|string $verbosity = false
     *
     * @return void
     */
    public function error($string, $verbosity = false)
    {
        if (is_array($string)) {
            $string = print_r($string, true);
        }

        parent::error('['.date('Y-m-d H:i:s').'] '.$string, $verbosity);
    }
}
