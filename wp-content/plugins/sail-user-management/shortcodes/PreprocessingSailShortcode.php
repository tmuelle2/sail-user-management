<?php

namespace Sail\Shortcodes;

abstract class PreprocessingSailShortcode extends SailShortcode
{
    public function hasPreprocessingRedirect(): bool
    {
        return true;
    }

    /**
     * This subclass assumes preprocessing is requied and requires
     * the imlplementation
     * @return mixed
     */
    abstract public function preprocessingCallback();
}
