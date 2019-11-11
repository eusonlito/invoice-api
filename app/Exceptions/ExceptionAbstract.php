<?php declare(strict_types=1);

namespace App\Exceptions;

use Exception;

abstract class ExceptionAbstract extends Exception
{
    /**
     * @var string
     */
    protected string $status = '';

    /**
     * @param ?string $message = null
     * @param ?int $code = 0
     * @param ?\Exception $previous = null
     * @param ?string $status = null
     *
     * @return self
     */
    public function __construct(?string $message = null, ?int $code = 0, ?Exception $previous = null, ?string $status = null)
    {
        $this->setStatus($status ?: '');

        parent::__construct($message ?: '', $code ?: $this->code, $previous);
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
