<?php

namespace Sail\Utils;

use DOMDocument;
use Sail\Constants;
use Sail\Data\Model\FriendshipConnectProfile;
use Sail\Data\Model\User;

final class HtmlUtils
{

    // NOTE REQUEST GLOBAL MUTABLE FLAG!
    // This flag gets sets when the common form handling logic has already been added to the page for the request.
    // This is used to avoid adding it twice
    private static $globalFormBaseAdded = false;

    // NOTE REQUEST GLOBAL MUTABLE LIST
    // This collects the form actions on the page to add them into the common variable injection script
    private static $globalFormActions = [];

    public final static function getSailTemplate(string $fileName, array $variables = array()): string
    {
        extract($variables);
        ob_start();
        include(Constants::TEMPLATE_DIR . $fileName);
        $doc = new DOMDocument();
        @$doc->loadHTML(ob_get_clean());
        $forms = $doc->getElementsByTagName('form');
        $formActions = [];
        foreach ($forms as $form) {
            $id = $form->attributes->getNamedItem('id');
            $action = $form->attributes->getNamedItem('action');
            if ($id !== null && $action !== null) {
                $formActions[] = ['id' => $id->value, 'action' => $action->value];
            }
        }
        if (!empty($formActions)) {
            if (!self::$globalFormBaseAdded) {
                ob_start();
                include(Constants::TEMPLATE_DIR . 'form-base.php');
                $js = new DOMDocument();
                $js->loadHTML(ob_get_clean());
                $import = $doc->importNode($js->getElementById('form-base'), true);
                $doc->getElementsByTagName('body')[0]->appendChild($import);
                self::$globalFormBaseAdded = true;
            }
            self::$globalFormActions = array_merge(self::$globalFormActions, $formActions);
        }
        return $doc->saveHTML($doc->getElementsByTagName('body')[0]);
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
            'nonce' => wp_create_nonce('wp_rest'),
            'formRestPrefix' => Constants::FORM_REST_PREFIX,
            'formActions' => self::$globalFormActions
        ];
        wp_add_inline_script(Constants::JS_COMMON_SCRIPT_HANDLE, 'const SAIL = ' . json_encode($params));
    }
}
