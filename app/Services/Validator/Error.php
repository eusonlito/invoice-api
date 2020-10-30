<?php declare(strict_types=1);

namespace App\Services\Validator;

use Exception;
use ErrorException;
use LogicException;
use RuntimeException;
use Throwable;

class Error
{
    /**
     * @param \Throwable $e
     *
     * @return bool
     */
    public static function set(Throwable $e): bool
    {
        if (static::isSystemException($e)) {
            report($e);
        }

        throw $e;
    }

    /**
     * @param \Throwable $e
     *
     * @return bool
     */
    protected static function isSystemException(Throwable $e): bool
    {
        return ($e instanceof ErrorException)
            || ($e instanceof RuntimeException)
            || ($e instanceof LogicException);
    }
}
