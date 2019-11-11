<?php declare(strict_types=1);

namespace App\Exceptions;

class NotFoundException extends ExceptionAbstract
{
    /**
     * @var int
     */
    protected $code = 404;
}
