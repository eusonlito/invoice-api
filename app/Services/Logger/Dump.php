<?php declare(strict_types=1);

namespace App\Services\Logger;

use Symfony\Component\VarDumper;

class Dump
{
    /**
     * @param mixed $var
     *
     * @return void
     */
    public static function dump($var)
    {
        $cloner = new VarDumper\Cloner\VarCloner();
        $cloner->setMaxItems(-1);

        static::dumper()->dump($cloner->cloneVar($var));
    }

    /**
     * @return \Symfony\Component\VarDumper\Dumper\AbstractDumper
     */
    protected static function dumper(): VarDumper\Dumper\AbstractDumper
    {
        if (PHP_SAPI === 'cli') {
            return new VarDumper\Dumper\CliDumper();
        }

        return new VarDumper\Dumper\HtmlDumper();
    }
}
