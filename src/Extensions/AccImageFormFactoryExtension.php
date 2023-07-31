<?php

namespace Iliain\Accessible\Extensions;

use SilverStripe\Forms\Tip;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TippableFieldInterface;
use SilverStripe\AssetAdmin\Forms\FileFormFactory;

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
        if ($this->getFormType($context) === FileFormFactory::TYPE_INSERT_MEDIA) {
            $fields->removeByName(['AltText', 'Caption']);
            $fields->insertBefore(
                'PreviewImage',
                LiteralField::create('AccInstruct', '<p>Click on the \'Details\' button above to edit Alt Text and Captions</p>')
            );
        } else {
            $altField = TextField::create(
                'AltText',
                _t('SilverStripe\\AssetAdmin\\Controller\\AssetAdmin.AltText', 'Alternative text (alt)')
            );
            $captionField = TextareaField::create(
                'Caption', 
                _t('SilverStripe\\AssetAdmin\\Controller\\AssetAdmin.Caption', 'Caption')
            );
            
            $titleField = $fields->fieldByName('Editor.Details.Title');
            if ($titleField) {
                if ($titleField->isReadonly()) $altField = $altField->performReadonlyTransformation();
                if ($titleField->isReadonly()) $captionField = $captionField->performReadonlyTransformation();
    
                $fields->insertAfter(
                    'AttributesDescription',
                    $captionField
                );

                $fields->insertAfter(
                    'Caption',
                    $altField
                );

                $altTextDescription = _t(
                    'SilverStripe\\AssetAdmin\\Controller\\AssetAdmin.AltTextTip',
                    'Description for visitors who are unable to view the image (using screenreaders or ' .
                    'image blockers). Recommended for images which provide unique context to the content.'
                );

                if ($altField instanceof TippableFieldInterface) {
                    $altField->setTip(new Tip($altTextDescription, Tip::IMPORTANCE_LEVELS['HIGH']));
                } else {
                    $altField->setDescription($altTextDescription);
                }
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
