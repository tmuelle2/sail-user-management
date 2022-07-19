<?php

namespace Sail\Utils;

use DOMDocument;
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
        $doc = new DOMDocument();
        $doc->loadHTML(ob_get_clean());
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
            extract(["formActions" => $formActions]);
            ob_start();
            include(Constants::TEMPLATE_DIR . 'form-base.php');
            $js = new DOMDocument();
            $js->loadHTML(ob_get_clean());
            $import = $doc->importNode($js->getElementById('form-base'), true);
            $doc->getElementsByTagName('body')[0]->appendChild($import);
        }
        return $doc->saveHTML();
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
}
