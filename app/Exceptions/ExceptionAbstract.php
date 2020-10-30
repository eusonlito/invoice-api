<?php declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

abstract class ExceptionAbstract extends Exception
{
    /**
     * @var string
     */
    protected string $status = '';

    /**
     * @param ?string $message = null
     * @param ?int $code = 0
     * @param ?\Throwable $previous = null
     * @param ?string $status = null
     *
     * @return self
     */
    public function __construct(?string $message = null, ?int $code = 0, ?Throwable $previous = null, ?string $status = null)
    {
        $this->setStatus((string)$status);

        parent::__construct((string)$message, $code ?: $this->code, $previous);
    }

    /**
     * @param string $status
     *
     * @return self
     */
    final public function setStatus(string $status): self
    {
        $this->status = $status ?: '';

        return $this;
    }

    /**
     * @return string
     */
    final public function getStatus(): string
    {
        return $this->status;
    }
}
