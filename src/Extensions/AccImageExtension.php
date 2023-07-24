<?php

namespace Iliain\Accessible\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\FieldType\DBField;

/**
 * Extends Silverstripe Images to include AltText and Caption fields
 *
 * @package silverstripe
 * @subpackage silverstripe-accessible
 *
 * @property string AltText
 * @property string Caption
 */
class AccImageExtension extends DataExtension
{
    /**
     * Additional database fields
     *
     * @var array
     */
    private static $db = [
        'AltText'    => 'Varchar(255)',
        'Caption'    => 'Text',
    ];

    /**
     * Gets the value of the AltText field
     * 
     * @return mixed
     */
    public function getAltText()
    {
        return $this->owner->getField('AltText');
    }

    /**
     * Silverstripe defaults to using the name as the alt text, which is not ideal
     * 
     * @param $attributes
     * @return mixed
     */
    public function updateAttributes(&$attributes)
    {
        $attributes['alt'] = $this->owner->getAltText();
    }

    /**
     * Provide a way to render an accessible template rather than the default
     * 
     * @return string
     */
    public function getAccessible()
    {
        $val = (string)$this->owner->renderWith([
            'Iliain/Accessible/Includes/AccessibleImage',
            'DBFile_image',
        ]);

        // Need to return as a HTMLFragment so it actually gets rendered as HTML in the template
        return DBField::create_field('HTMLFragment', $val);
    }
}
