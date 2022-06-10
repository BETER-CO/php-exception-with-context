<?php

namespace Beter\Yii2\Exception;

/**
 * Interface of the exception with context.
 *
 * Objects of class that implement this interface may store `context` data that may be used for further processing.
 */
interface ExceptionWithContextInterface
{
    /**
     * Gets context of the exception.
     *
     * @return array
     */
    public function getContext(): array;

    /**
     * Sets the context for the exception.
     *
     * @param array $context
     *
     * @return $this
     */
    public function setContext(array $context): self;
}
