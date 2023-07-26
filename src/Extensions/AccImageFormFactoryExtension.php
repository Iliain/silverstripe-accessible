<?php

namespace Iliain\Accessible\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextareaField;
use SilverStripe\AssetAdmin\Forms\FileFormFactory;
use SilverStripe\Forms\LiteralField;

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

        if ($this->getFormType($context) === FileFormFactory::TYPE_INSERT_MEDIA) {
            $fields->insertBefore(
                'PreviewImage',
                LiteralField::create('AccInstruct', '<p>Click on the \'Details\' button above to edit Alt Text and Captions</p>')
            );
        } else {
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

    /**
     * Get form type from 'type' context
     *
     * @param array $context
     * @return string
     */
    protected function getFormType($context)
    {
        return empty($context['Type']) ? FileFormFactory::TYPE_ADMIN : $context['Type'];
    }
}
