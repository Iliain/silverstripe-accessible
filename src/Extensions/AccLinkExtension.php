<?php

namespace Iliain\Accessible\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Control\Director;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\FieldType\DBField;

/**
 * Class AccLinkExtension
 * @package Iliain\Accessible\Extensions
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

        $this->owner->AccessibleType = $this->owner->getAccessibleTypeVal();
    }

    public function updateCMSFields(FieldList $fields)
    {
        $fields->insertAfter('Title', TextField::create('AccessibleText', 'Accessible Text')->setDescription('e.g. "Read more about our article on lorem ipsum here"'));
    }

    /**
     * Determine the type of link
     * @return string
     */
    public function getAccessibleTypeVal()
    {
        $url = $this->owner->getLinkURL();

        if ((class_exists("gorriecoe\\Link\\Models\\Link") && $this->owner->ClassName == \gorriecoe\Link\Models\Link::class) || 
            (class_exists("Sheadawson\\Linkable\\Models\\Link") && $this->owner->ClassName == \Sheadawson\Linkable\Models\Link::class)) {
            $type = $this->owner->Type;
        } else {
            return 'Internal';
        }

        $accType = null;
        switch ($type) {
            case 'URL':
                if (strpos($url, Director::absoluteBaseURL()) === false) {
                    $accType = 'External';
                } else {
                    return 'Internal';
    
                    if (strpos($url, '#') !== false) {
                        $accType = 'Anchor';
                    }
                }
                break;
            case 'SiteTree':
                $accType = 'Internal';

                if ($this->owner->Anchor) {
                    $cleanURL = str_replace($this->owner->Anchor, '', $url);
                    $currentURL = Director::get_current_page()->Link();

                    if ($currentURL == $cleanURL) {
                        $accType = 'Anchor';
                    }
                }
                break;
            case 'Email':
                $accType = 'Email';
                break;
            case 'Phone':
                $accType = 'Phone';
                break;
            case 'File':
                $accType = 'Download';
                break;
            default:
                $accType = 'Internal';
                break;
        }

        return $accType;
    }

    /**
     * Attempts to return a string in the following format: 
     * "Internal Link: Read more about our article on lorem ipsum here (opens in a new window)"
     * @return string
     */
    public function getAccessibleDescription()
    {
        $text = $this->owner->AccessibleType . ' Link';

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
