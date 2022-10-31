<?php

namespace Sail\Utils;

use Sail\Constants;
use Sail\Data\Model\FriendshipConnectProfile;
use Sail\Data\Model\User;

final class HtmlUtils
{
    public final static function getSailTemplate(string $fileName, array $variables = array()): string
    {
        extract($variables);
        ob_start();
        include(Constants::TEMPLATE_DIR . $fileName);
        return ob_get_clean();
    }

    public final static function getUserFormData(array $params, ?User $currentUser = null): User
    {
        $data = array();
        foreach (User::fieldKeys() as $element => $format) {
            if (isset($params[$element])) {

                // Special check if the data is an array for multi-select checkbox inputs
                if (is_array($params[$element])) {
                    if (!empty($params[$element])) {
                        $combined = "";
                        foreach ($params[$element] as $check) {
                            $combined .= $check;
                            $combined .= "|"; // using piped seperated string since commas are used
                        }
                        $data[$element] = substr($combined, 0, -1);
                    }
                } else {
                    $data[$element] = $params[$element];
                }
            } else {
                $data[$element] = isset($currentUser) ? $currentUser[$element] : null;
            }
        }
        return new User($data);
    }

    public final static function getFriendshipConnectProfileFormData(array $params, ?FriendshipConnectProfile $curMember = null): FriendshipConnectProfile
    {
        $data = array();
        foreach (FriendshipConnectProfile::fieldKeys() as $element => $format) {
            if (isset($params[$element])) {
                $data[$element] = $params[$element];
            } else {
                $data[$element] = isset($curMember) ? $curMember[$element] : null;
            }
            $formats[] = $format;
        }
        return new FriendshipConnectProfile($data);
    }


    public final static function getSailPage(string $fileName, array $variables = array()): string
    {
        extract($variables);
        ob_start();
        include(Constants::HTML_DIR . $fileName);
        return ob_get_clean();
    }

    public final static function addCommonJs(): void {
        $params = [
            'nonce' => wp_create_nonce('wp_rest'), // TODO: Replace with enqueueing or adding dependency on 'wp-api' and using the wpApiSettings object
            'formsApiUrl' => WebUtils::getFormsApiUrl(),
        ];
        wp_add_inline_script(Constants::JS_COMMON_SCRIPT_HANDLE, 'const SAIL = ' . json_encode($params));
    }

    public final static function getSailButton(string $form, string $text): string
    {
        return "<button type='submit' form='$form' value='Submit' class='loadingButton wp-block-button__link has-white-color has-vivid-cyan-blue-background-color has-text-color has-background'>$text</button>";
    }
}
