<?php

namespace Iliain\Accessible\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextareaField;

/**
 * Extends ImageFormFactory to add the fields necessary to edit AltText
 * and Caption
 *
 * @package silverstripe
 * @subpackage silverstripe-accessible
 */
class AccImageFormFactoryExtension extends Extension
{
    /**
     * @param FieldList $fields
     * @param $controller
     * @param $formName
     * @param $context
     */
    public function updateFormFields(FieldList $fields, $controller, $formName, $context)
    {
        $fields->removeByName(['AltText', 'Caption']);

        $altField = TextareaField::create('AltText', 'Alt Text');
        $captionField = TextareaField::create('Caption', 'Caption');

        $titleField = $fields->fieldByName('Editor.Details.Title');
        if ($titleField) {
            if ($titleField->isReadonly()) $altField = $altField->performReadonlyTransformation();
            if ($titleField->isReadonly()) $captionField = $captionField->performReadonlyTransformation();

            $fields->insertAfter(
                'LastEdited',
                $altField
            );

            $fields->insertAfter(
                'AltText',
                $captionField
            );
        }
    }
}
