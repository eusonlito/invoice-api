<?php declare(strict_types=1);

namespace App\Services\Sign;

class SignFactory
{
    /**
     * @return \App\Services\Sign\SignAbstract
     */
    public static function get(): SignAbstract
    {
        return new KSI\KSI();
    }
}
