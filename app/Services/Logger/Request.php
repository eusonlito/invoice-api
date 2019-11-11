<?php declare(strict_types=1);

namespace App\Services\Logger;

class Request extends LoggerRotatingFileAbstract
{
    /**
     * @var string
     */
    protected static string $name = 'requests';
}
