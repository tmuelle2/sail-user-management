<?php

namespace Sail\Utils;

use DOMDocument;
use Sail\Data\Model\SailDataObject;

final class FormUtils
{
    public const STATE_OPTIONS = array(
        '<option value="AL">Alabama</option>',
        '<option value="AK">Alaska</option>',
        '<option value="AZ">Arizona</option>',
        '<option value="AR">Arkansas</option>',
        '<option value="CA">California</option>',
        '<option value="CO">Colorado</option>',
        '<option value="CT">Connecticut</option>',
        '<option value="DE">Delaware</option>',
        '<option value="DC">District Of Columbia</option>',
        '<option value="FL">Florida</option>',
        '<option value="GA">Georgia</option>',
        '<option value="HI">Hawaii</option>',
        '<option value="ID">Idaho</option>',
        '<option value="IL">Illinois</option>',
        '<option value="IN">Indiana</option>',
        '<option value="IA">Iowa</option>',
        '<option value="KS">Kansas</option>',
        '<option value="KY">Kentucky</option>',
        '<option value="LA">Louisiana</option>',
        '<option value="ME">Maine</option>',
        '<option value="MD">Maryland</option>',
        '<option value="MA">Massachusetts</option>',
        '<option value="MI">Michigan</option>',
        '<option value="MN">Minnesota</option>',
        '<option value="MS">Mississippi</option>',
        '<option value="MO">Missouri</option>',
        '<option value="MT">Montana</option>',
        '<option value="NE">Nebraska</option>',
        '<option value="NV">Nevada</option>',
        '<option value="NH">New Hampshire</option>',
        '<option value="NJ">New Jersey</option>',
        '<option value="NM">New Mexico</option>',
        '<option value="NY">New York</option>',
        '<option value="NC">North Carolina</option>',
        '<option value="ND">North Dakota</option>',
        '<option value="OH">Ohio</option>',
        '<option value="OK">Oklahoma</option>',
        '<option value="OR">Oregon</option>',
        '<option value="PA">Pennsylvania</option>',
        '<option value="RI">Rhode Island</option>',
        '<option value="SC">South Carolina</option>',
        '<option value="SD">South Dakota</option>',
        '<option value="TN">Tennessee</option>',
        '<option value="TX">Texas</option>',
        '<option value="UT">Utah</option>',
        '<option value="VT">Vermont</option>',
        '<option value="VA">Virginia</option>',
        '<option value="WA">Washington</option>',
        '<option value="WV">West Virginia</option>',
        '<option value="WI">Wisconsin</option>'
    );

    public const SITUATION_CHECKBOXES = array(
        '<div>' .
            '<input type="checkbox" name="situation[]" id="situation1" value="Need to commit to understanding steps involved in developing a housing solution.">' .
            '<label for="situation1">Need to commit to understanding steps involved in developing a housing solution</label>' .
        '</div>',
        '<div>' .
            '<input type="checkbox" name="situation[]" id="situation2" value="Need support to understand Government Benefits like SSI, SSDI, Medicaid, Bridge Card, etc.">' .
            '<label for="situation2">Need support to understand Government Benefits like SSI, SSDI, Medicaid, Bridge Card, etc</label>' .
        '</div>',
        '<div>' .
            '<input type="checkbox" name="situation[]" id="situation3" value="Need to connect with others to develop friendships/roommates">' .
            '<label for="situation3">Need to connect with others to develop friendships/roommates</label>' .
        '</div>',
        '<div>' .
            '<input type="checkbox" name="situation[]" id="situation4" value="Interested in learning about different housing models">' .
            '<label for="situation4">Interested in learning about different housing models</label>' .
        '</div>',
        '<div>' .
            '<input type="checkbox" name="situation[]" id="situation5" value="Interested in developing a support model and finding staff">' .
            '<label for="situation5">Interested in developing a support model and finding staff</label>' .
        '</div>',
        '<div>' .
            '<input type="checkbox" name="situation[]" id="situation6" value="Need information on how to prepare for moving into a new place">' .
            '<label for="situation6">Need information on how to prepare for moving into a new place</label>' .
        '</div>',
        '<div>' .
            '<input type="checkbox" name="situation[]" id="situation7" value="Need information and resources that can help in the transition to a new place">' .
            '<label for="situation7">Need information and resources that can help in the transition to a new place</label>' .
        '</div>',
        '<div>' .
            '<input type="checkbox" name="situation[]" id="situation8" value="Interested in supporting and maintaining an existing housing solution">' .
            '<label for="situation8">Interested in supporting and maintaining an existing housing solution</label>' .
        '</div>'
    );

    public const ROLE_OPTIONS = array(
        '<option value="I\'m a Parent of a child with a disability">I\'m a Parent of a child with a disability</option>',
        '<option value="I\'m a Person with a disability">I\'m a Person with a disability</option>',
        '<option value="I\'m a brother or sister of a child with a disability">I\'m a brother or sister of a child with a disability</option>',
        '<option value="I\'m a friend or relative of a child with a disability">I\'m a friend or relative of a child with a disability</option>',
        '<option value="I work as an advocate, service provider, or in the special needs service industry">I work as an advocate, service provider, or in the special needs service industry</option>',
        '<option value="Other">Other</option>'
    );

    public const TIMEFRAME_OPTIONS = array(
        '<option value="asap">As soon as possible</option>',
        '<option value="1year">Within the next year</option>',
        '<option value="3years">Within 3 years</option>',
        '<option value="5years">Within 5 years</option>',
        '<option value="noplans">No set plans as of now</option>'
    );

    public const CONTACT_EMAIL_RADIO = array(
        '<input checked="checked" name="contactViaEmail" type="radio" value="1" /> Yes<br />',
        '<input name="contactViaEmail" type="radio" value="0" /> No <br />'
    );

    public const CONTACT_TEXT_RADIO = array(
        '<input checked="checked" name="contactViaText" type="radio" value="1" /> Yes<br />',
        '<input name="contactViaText" type="radio" value="0" /> No <br />'
    );

    public const NEWSLETTER_RADIO = array(
        '<input checked="checked" name="newsletter" type="radio" value="1" /> Yes<br />',
        '<input name="newsletter" type="radio" value="0" /> No<br />'
    );

    public static function populateSelectOption(string $target, array $options): string
    {
        $trgt = "\"$target\"";
        ob_start();
        foreach ($options as $opt) {
            echo str_replace($trgt, "$trgt selected", $opt);
        }
        return ob_get_clean();
    }

    public static function populateMultiSelectChecbox(array $checkedVals, array $checkboxes): string
    {
        $mapFn = fn(string $str): string => "/(\"$str\")/";
        $situations = array_map($mapFn,  $checkedVals);
        ob_start();
        foreach($checkboxes as $checkbox) {
          echo preg_replace($situations, '${1} selected', $checkbox);
        }
        return ob_get_clean();
    }

    public static function populateRadio(string $target, array $radio): string
    {
        $trgt = "\"$target\"";
        $checked = 'checked="checked"'; 
        ob_start();
        foreach ($radio as $rdio) {
            if (str_contains($rdio, $trgt)) {
                if (!str_contains($rdio, $checked)) {
                    echo str_replace('input ', "input $checked", $rdio);
                }
            } else {
                echo str_replace($checked, '', $rdio);
            }
        }
        return ob_get_clean();
    }

    /**
     * Attempts to update an html form's inputs with the values of a database object using
     * the field names and input tag names to populate fields.
     * TODO deprecated, delete?
     */
    public static function populateFormElements(DOMDocument $domDoc, SailDataObject $dbObj): void
    {
        $tags = array('input', 'select', 'textarea');
        $elementMap = array();
        foreach ($tags as $tag) {
            // get elements 
            $elementList = $domDoc->getelementsbytagname($tag);

            // build tag to name to node associative arrays
            $elementMap[$tag] = self::nameToNodeMap($elementList);
        }

        // populate elements 
        foreach ($dbObj->dataKeys() as $element) {
            foreach ($tags as $tag) {
                if (isset($elementMap[$tag][$element]) && isset($dbObj[$element])) {
                    switch ($tag) {
                        case 'input':
                            self::populateInput($elementMap[$tag][$element], $dbObj[$element]);
                            break;
                        case 'select':
                            self::populateSelect($elementMap[$tag][$element], $dbObj[$element]);
                            break;
                        default:
                            self::popluateElement($elementMap[$tag][$element], $dbObj[$element]);
                            break;
                    }
                    continue;
                }
            }
        }
    }

    // returns a mapping a list of dom nodes' name attribute value to the node(s) with that name
    private static function nameToNodeMap($nodes): array
    {
        $arr = array();
        foreach ($nodes as $node) {
            $nodeName = $node->attributes->getnameditem('name');
            $cleanName = str_ireplace("[]", "", $nodeName->nodevalue);
            if ($nodeName != null) {
                if (isset($arr[$cleanName])) {
                    array_push($arr[$cleanName], $node);
                } else {
                    $arr[$cleanName] = array($node);
                }
            }
        }
        return $arr;
    }

    // Populates vanilla (text, data, etc.) and radio input elements with value
    private static function populateInput($domInput, $value): void
    {
        $count = count($domInput);
        if ($count > 1 && $domInput[0]->attributes->getnameditem('type')->nodevalue == 'radio') {
            for ($i = 0; $i < $count; $i++) {
                if ($domInput[$i]->attributes->getnameditem('value')->nodevalue == $value) {
                    $domInput[$i]->setattribute('checked', '');
                } else {
                    $domInput[$i]->removeattribute('checked');
                }
            }
        } elseif ($count > 1 && $domInput[0]->attributes->getnameditem('type')->nodevalue == 'checkbox') {
            $value_array = explode("|", $value);
            for ($i = 0; $i < $count; $i++) {
                if (in_array($domInput[$i]->attributes->getnameditem('value')->value, $value_array)) {
                    $domInput[$i]->setattribute('checked', '');
                }
            }
        } elseif ($count > 1 && $domInput[0]->attributes->getnameditem('type')->nodevalue == 'hidden') {
            // do nothing for now
            //$dom_input[0]->setattribute('value', $value);
        } elseif ($count == 1) {
            $domInput[0]->setattribute('value', $value);
        } else {
            $attrs = self::domNamedNodeMapToString($domInput[0]->attributes);
            error_log("unsupported input element(s). there are $count elements, the first element's attributes are: $attrs");
        }
    }

    // Populates a dom select element with the correct option selected if it exists
    private static function populateSelect($domSelect, $option): void
    {
        if (count($domSelect) > 1) {
            return;
        }
        $children = $domSelect[0]->childnodes;
        for ($i = 0; $i < $children->length; ++$i) {
            if (
                $children->item($i)->attributes != null
                && $children->item($i)->attributes->getnameditem('value') != null
                && $children->item($i)->attributes->getnameditem('value')->nodevalue == $option
            ) {
                $children->item($i)->setattribute('selected', '');
            }
        }
    }

    private static function popluateElement($domElement, $value): void
    {
        if (count($domElement) > 1) {
            return;
        }
        $domElement[0]->nodevalue = $value;
    }

    private static function domNamedNodeMapToString($map): string
    {
        $count = $map->count();
        $str = "";
        for ($i = 0; $i < $count; ++$i) {
            $str .= print_r($map->item($i), true);
        }
        return $str;
    }
}
