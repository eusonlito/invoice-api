<?php declare(strict_types=1);

namespace App\Exceptions;

class AuthenticationException extends ExceptionAbstract
{
    /**
     * @var int
     */
    protected $code = 401;
}
