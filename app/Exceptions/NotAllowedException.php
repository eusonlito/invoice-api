<?php declare(strict_types=1);

namespace App\Exceptions;

class NotAllowedException extends ExceptionAbstract
{
    /**
     * @var int
     */
    protected $code = 403;
}
