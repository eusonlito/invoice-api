<?php declare(strict_types=1);

namespace App\Services\Sign;

abstract class SignAbstract
{
    /**
     * @param string $file
     * @param string $certificate
     * @param string $password = ''
     *
     * @return string
     */
    abstract public function sign(string $file, string $certificate, string $password = ''): string;
}
