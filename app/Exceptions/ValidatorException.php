<?php declare(strict_types=1);

namespace App\Exceptions;

class ValidatorException extends ExceptionAbstract
{
    /**
     * @var int
     */
    protected $code = 422;
}
