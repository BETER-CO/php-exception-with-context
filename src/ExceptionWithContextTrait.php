<?php

namespace Beter\ExceptionWithContext;

trait ExceptionWithContextTrait
{
    protected array $context;

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
