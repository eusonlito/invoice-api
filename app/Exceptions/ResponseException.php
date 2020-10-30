<?php declare(strict_types=1);

namespace App\Exceptions;

use ErrorException;
use RuntimeException;
use Exception;
use Throwable;

use Illuminate\Auth\AuthenticationException as BaseAuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class ResponseException extends ExceptionAbstract
{
    /**
     * @param \Throwable $e
     *
     * @return self
     */
    public static function fromException(Throwable $e): self
    {
        return new self(
            static::getMessageFromException($e),
            static::getCodeFromException($e),
            $e,
            static::getStatusFromException($e)
        );
    }

    /**
     * @param \Throwable $e
     *
     * @return string
     */
    protected static function getMessageFromException(Throwable $e): string
    {
        if ($e instanceof TokenExpiredException) {
            return __('exception.token-expired');
        }

        if ($e instanceof TokenBlacklistedException) {
            return __('exception.token-blacklisted');
        }

        if ($e instanceof TokenInvalidException) {
            return __('exception.token-invalid');
        }

        if ($e instanceof JWTException) {
            return __('exception.token-empty');
        }

        if ($e instanceof BaseAuthenticationException) {
            return __('exception.auth-empty');
        }

        if ($e instanceof AuthenticationException) {
            return __('exception.auth-error');
        }

        if ($e instanceof NotFoundHttpException) {
            return __('exception.not-found');
        }

        if ($e instanceof ModelNotFoundException) {
            return __('exception.not-found-model');
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return __('exception.not-allowed');
        }

        if ($e instanceof QueryException) {
            return __('exception.query');
        }

        if (static::isSystemException($e)) {
            return __('exception.system');
        }

        return $e->getMessage();
    }

    /**
     * @param \Throwable $e
     *
     * @return string
     */
    protected static function getStatusFromException(Throwable $e): string
    {
        if (method_exists($e, 'getStatus') && ($status = $e->getStatus())) {
            return $status;
        }

        if ($e instanceof TokenExpiredException) {
            return 'token_expired';
        }

        if ($e instanceof TokenBlacklistedException) {
            return 'token_blacklist';
        }

        if ($e instanceof TokenInvalidException) {
            return 'token_invalid';
        }

        if ($e instanceof JWTException) {
            return 'token_empty';
        }

        if ($e instanceof BaseAuthenticationException) {
            return 'user_auth';
        }

        if ($e instanceof AuthenticationException) {
            return 'user_error';
        }

        if ($e instanceof NotFoundHttpException) {
            return 'not_found';
        }

        if ($e instanceof ModelNotFoundException) {
            return 'not_available';
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return 'method_not_allowed';
        }

        if ($e instanceof NotAllowedException) {
            return 'not_allowed';
        }

        if ($e instanceof ValidatorException) {
            return 'validator_error';
        }

        if ($e instanceof QueryException) {
            return 'query_error';
        }

        if (static::isSystemException($e)) {
            return 'system_error';
        }

        return 'error';
    }

    /**
     * @param \Throwable $e
     *
     * @return int
     */
    protected static function getCodeFromException(Throwable $e): int
    {
        if ($e instanceof ModelNotFoundException) {
            return 404;
        }

        if (static::isAuthException($e)) {
            return 401;
        }

        $code = (int)(method_exists($e, 'getStatusCode') ? $e->getStatusCode() : $e->getCode());

        return (($code >= 400) && ($code < 600)) ? $code : 500;
    }

    /**
     * @param \Throwable $e
     *
     * @return bool
     */
    protected static function isAuthException(Throwable $e): bool
    {
        return ($e instanceof TokenExpiredException)
            || ($e instanceof TokenBlacklistedException)
            || ($e instanceof TokenInvalidException)
            || ($e instanceof JWTException)
            || ($e instanceof BaseAuthenticationException);
    }

    /**
     * @param \Throwable $e
     *
     * @return bool
     */
    protected static function isSystemException(Throwable $e): bool
    {
        return ($e instanceof FatalThrowableError)
            || ($e instanceof ErrorException)
            || ($e instanceof RuntimeException);
    }
}
