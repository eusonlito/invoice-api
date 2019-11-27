<?php
namespace App\Services\Sign\KSI;

use App\Services\Sign\SignAbstract;

class KSI extends SignAbstract
{
    /**
     * @param string $file
     * @param string $certificate
     * @param string $password = ''
     *
     * @return string
     */
    public function sign(string $file, string $certificate, string $password = ''): string
    {
        return (new CommandSign())->sign($file, $certificate, $password);
    }

    /**
     * @param string $file
     * @param string $certificate
     *
     * @return string
     */
    public function verify(string $certificate, string $password = ''): string
    {
        return (new CommandVerify())->verify($certificate, $password);
    }
}
