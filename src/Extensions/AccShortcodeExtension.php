<?php

namespace Iliain\Accessible\Extensions;

use DOMDocument;
use SilverStripe\Assets\Image;
use SilverStripe\Core\Extension;
use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Config\Config;
use SilverStripe\View\ArrayData;
use SilverStripe\View\ViewableData;

/**
 * Extends the shortcode parser to allow AltText and Captions to appear in WYSIWYG images
 *
 * @package silverstripe
 * @subpackage silverstripe-accessible
 */
class AccShortcodeExtension extends Extension
{
    /**
     * Apply our custom parser code only on the frontend
     *
     * @param string $content
     * @return void
     */
    public function onAfterParse(&$content)
    {
        $config = Config::inst()->get('Iliain\Accessible\Config', 'settings')['enable_image_shortcode'];

        if ($config) {
            $isCMS = Controller::curr() instanceof LeftAndMain;

            if (!$isCMS) {
                if ($content) {
                    $doc = new DOMDocument();
                    @$doc->loadHTML($content);

                    $content = $this->applyImageTemplate($doc);
                }
            }
        }        
    }

    /**
     * Alter and return the images in an accessible format
     *
     * @param string $content
     * @return void
     */
    public function applyImageTemplate($doc)
    {
        $template = Config::inst()->get('Iliain\Accessible\Config', 'customise')['image_shortcode_template'];
        if (!$template) {
            return;
        }

        $tags = $doc->getElementsByTagName('img');
        foreach ($tags as $tag) {
            $attributeArr = [];

            if ($tag->hasAttributes()) {
                foreach ($tag->attributes as $attr) {
                    $attributeArr[$attr->nodeName] = $attr->nodeValue;
                }
            }

            // @todo - find better way of getting these attributes
            $image = Image::get()->filter([
                'FileFilename' => str_replace('/assets/', '', $attributeArr['src'])
            ])->first();
            if ($image) {
                $attributeArr['alt'] = $image->AltText;
                $attributeArr['caption'] = $image->Caption;
            }

            $attrData = ArrayData::create($attributeArr);
            $viewableData = ViewableData::create();
            $render = $viewableData->renderWith($template, $attrData);

            $newNode = $doc->createDocumentFragment();
            $newNode->appendXML($render->getValue());
            $tag->parentNode->replaceChild($newNode, $tag);
        }

        return $doc->saveHTML();
    }
}
