<?php

namespace Sail\Shortcodes;

use Sail\Constants;
use Sail\Utils\HtmlUtils;
use Sail\Utils\Singleton;

final class MessageShortCodes extends ShortCodeRegistrator
{
    use Singleton;

    public function getShortcodes(): array {
        return [
            /**
             * displayMessage shortcode
             */
            new class extends SailShortcode {
                public function getName(): string
                {
                    return 'displayMessage';
                }
                public function getShortcodeContent(): string
                {
                    return HtmlUtils::getSailPage('display-message.html');
                }
            }
        ];
    }
}
