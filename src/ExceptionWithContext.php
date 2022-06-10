<?php

namespace Beter\Yii2\Exception;

/**
 * Implementation of the exception with context.
 *
 * Objects of this class may store `context` data that may be used for further processing.
 */
class ExceptionWithContext extends \Exception implements ExceptionWithContextInterface
{
    /**
     * Context data
     *
     * @var array
     */
    protected array $context;

    /**
     * @param string $message
     * @param mixed $code exception code
     * @param \Throwable|null $previous previous Throwable (https://php.net/manual/en/throwable.getprevious.php)
     * @param array $context key-value array with context data you want to store in this exception for further
     *   processing
     */
    public function __construct($message = "", $code = 0, \Throwable $previous = null, array $context = [])
    {
        parent::__construct($message, $code, $previous);

        $this->context = $context;
    }

    /**
     * Gets context of the exception.
     *
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Sets the context for the exception.
     *
     * @param array $context
     *
     * @return $this
     */
    public function setContext(array $context): self
    {
        $this->context = $context;
        return $this;
    }
}