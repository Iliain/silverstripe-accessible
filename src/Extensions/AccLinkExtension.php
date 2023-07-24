<?php

namespace Iliain\Accessible\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Control\Director;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\FieldType\DBField;
use gorriecoe\Link\Models\Link as GorrieCoeLink;
use Sheadawson\Linkable\Models\Link as SheadawsonLink;

/**
 * Extends Links to provide accessible information to the user
 *
 * @package silverstripe
 * @subpackage silverstripe-accessible
 * 
 * @property string AccessibleText
 * @property string AccessibleType
 */
class AccLinkExtension extends DataExtension
{
    private static $db = [
        'AccessibleText' => 'Varchar(255)',
        'AccessibleType' => 'Varchar(20)'
    ];

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        $this->owner->AccessibleType = $this->owner->getBaseAccessibleType();
    }

    public function updateCMSFields(FieldList $fields)
    {
        $fields->insertAfter('Title', TextField::create('AccessibleText', 'Accessible Text')->setDescription('e.g. "Read more about our article on lorem ipsum here"'));
    }

    /**
     * Determine the type of link. Options are:
     * - Internal
     * - External
     * - Download
     * - Email
     * - Phone
     * 
     * @return string
     */
    public function getBaseAccessibleType()
    {
        $url = $this->owner->getLinkURL();
        
        // @todo implement additional checks for differences between the two modules if necessary
        if ((class_exists("gorriecoe\\Link\\Models\\Link") && $this->owner->ClassName == GorrieCoeLink::class) || 
            (class_exists("Sheadawson\\Linkable\\Models\\Link") && $this->owner->ClassName == SheadawsonLink::class)) {
            $type = $this->owner->Type;
        } else {
            $type = null;
        }

        switch ($type) {
            case 'URL':
                if (strpos($url, Director::absoluteBaseURL()) === false) {
                    return 'External';
                } else {
                    return 'Internal';
                }
                break;
            case 'SiteTree':
                return 'Internal';
                break;
            case 'Email':
                return 'Email';
                break;
            case 'Phone':
                return 'Phone';
                break;
            case 'File':
                return 'Download';
                break;
            default:
                return 'Internal';
                break;
        }
    }

    /**
     * Dynamically determine if the link is an Anchor on the current page
     *
     * @return string
     */
    public function getDynamicAccessibleType()
    {
        $type = $this->owner->AccessibleType;

        if ($type == 'Internal' || $type == 'External') {
            $isCurrentAnchor = $this->checkIfCurrentPageAnchor();
            $type = $isCurrentAnchor ? $isCurrentAnchor : $type;
        }

        return $type;
    }

    /**
     * Checks if the Link is the same as the current page and has an Anchor
     *
     * @return string|void
     */
    public function checkIfCurrentPageAnchor()
    {
        $anchor = $this->owner->Anchor;
        $url = $this->owner->getLinkURL();
        $currentURL = Director::get_current_page()->AbsoluteLink();

        if ($this->owner->Type == 'SiteTree' && $anchor) {
            $cleanURL = str_replace($anchor, '', $url);
            if ($currentURL == $cleanURL) {
                return 'Anchor';
            }
        } else {
            if (strpos($url, '#') !== false) {
                $splitURL = explode('#', $url);
                if ($currentURL == $splitURL[0]) {
                    return 'Anchor';
                }
            }
        }
    }

    /**
     * Attempts to return a string in the following format: 
     * "Internal Link: Read more about our article on lorem ipsum here (opens in a new window)"
     * 
     * @return string
     */
    public function getAccessibleDescription()
    {
        $text = $this->owner->getDynamicAccessibleType() . ' Link';

        if ($this->owner->AccessibleText) {
            $text .= ': ' . $this->owner->AccessibleText;
        }

        if ($this->owner->OpenInNewWindow) {
            $text .= ' (opens in a new window)';
        }

        $this->owner->extend('updateAccessibleDescription', $text);

        return $text;
    }

    /**
     * Provide a way to render an accessible template rather than the default
     * 
     * @return string
     */
    public function getAccessible()
    {
        $val = (string)$this->owner->renderWith([
            'Iliain/Accessible/Includes/AccessibleLink',
            'Link',
        ]);

        // Need to return as a HTMLFragment so it actually gets rendered as HTML in the template
        return DBField::create_field('HTMLFragment', $val);
    }
}
