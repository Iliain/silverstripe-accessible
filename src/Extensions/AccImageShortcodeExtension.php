<?php

namespace Iliain\Accessible\Extensions;

use DOMDocument;
use SilverStripe\Assets\Image;
use SilverStripe\Core\Extension;
use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Control\Controller;

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
        $isCMS = Controller::curr() instanceof LeftAndMain;

        if (!$isCMS) {
            if ($content) {
                // @todo - add config to allow disabling of this feature
                $doc = new DOMDocument();
                @$doc->loadHTML($content);

                $content = $this->applyImageTemplate($doc);
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

            $newTemplate = <<<HTML
<figure class="{$attributeArr['class']}">
    <img src="{$attributeArr['src']}" alt="{$attributeArr['alt']}" width="{$attributeArr['width']}" height="{$attributeArr['height']}" loading="{$attributeArr['loading']}" />
    <figcaption>{$attributeArr['caption']}</figcaption>
</figure>
HTML;

            $this->owner->extend('updateAccessibleTemplate', $newTemplate, $tag);

            $newNode = $doc->createDocumentFragment();
            $newNode->appendXML($newTemplate);
            $tag->parentNode->replaceChild($newNode, $tag);
        }

        return $doc->saveHTML();
    }
}
