<?php

namespace Sail\Shortcodes;

abstract class SailShortcode
{
    /**
     * The name of the shortcode, the string that will be used to add it to pages.
     * @return string the shortcode name
     */
    abstract public function getName(): string;
    /**
     * Creates and returns the HTML context of the shortcode.
     * @return string HTML content of shortcode
     */
    abstract public function getShortcodeContent(): string;

    /**
     * Returns true if the shortcode has a preprocessing directive to
     * executer earlier in the action handler chain.  For example.
     * the short code needs to redirect based on logged in state.
     * @return bool if this shortcode requires preprocessing.
     */
    public function hasPreprocessingRedirect(): bool
    {
        return false;
    }
}
