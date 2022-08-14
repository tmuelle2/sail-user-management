<?php

namespace Sail\Shortcodes;

use Sail\Utils\Logger;

/**
 * A base class which uses an array of SailShortcodes provided by implementation.
 * The generated short codes will use the attributes of each shortcode
 * to add them and if necessary registering their preprocessing callback.
 */
abstract class ShortCodeRegistrator
{
    use Logger;

    abstract public function getShortcodes(): array;

    public function registerShortcodes(): void
    {
        foreach ($this->getShortcodes() as $shortcode) {
            add_shortcode($shortcode->getName(), [$shortcode, 'getShortcodeContent']);
            if ($shortcode->hasPreprocessingRedirect()) {
                add_action('template_redirect', [$shortcode, 'preprocessingCallback']);
            }
        }
    }
}
