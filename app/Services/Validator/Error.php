<?php declare(strict_types=1);

namespace App\Services\Validator;

use Exception;
use ErrorException;
use LogicException;
use RuntimeException;

class Error
{
    /**
     * @param \Exception $e
     *
     * @return bool
     */
    public static function set(Exception $e): bool
    {
        if (static::isSystemException($e)) {
            report($e);
        }

        throw $e;
    }

    /**
     * @param \Exception $e
     *
     * @return bool
     */
    protected static function isSystemException(Exception $e): bool
    {
        return ($e instanceof ErrorException)
            || ($e instanceof RuntimeException)
            || ($e instanceof LogicException);
    }
}
