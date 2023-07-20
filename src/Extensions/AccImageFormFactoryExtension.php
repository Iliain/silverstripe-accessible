<?php

namespace Iliain\Accessible\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextareaField;

/**
 * Class AccImageFormFactoryExtension
 * @package Iliain\Accessible\Extensions
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
