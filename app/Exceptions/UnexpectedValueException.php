<?php declare(strict_types=1);

namespace App\Exceptions;

class UnexpectedValueException extends ExceptionAbstract
{
    /**
     * @var int
     */
    protected $code = 412;
}
