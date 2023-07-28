<?php

namespace Iliain\Accessible\Extensions;

use DOMDocument;
use SilverStripe\Core\Extension;
use SilverStripe\View\ArrayData;
use SilverStripe\Control\Director;
use SilverStripe\Admin\LeftAndMain;
use SilverStripe\View\ViewableData;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Config\Config;
use Iliain\Accessible\ShortcodeProviders\AccLinkShortcodeProvider;

/**
 * Extends the shortcode parser to allow AltText and Captions to appear in WYSIWYG images, 
 * and to add accessible information to links
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
                $doc = new DOMDocument();
                @$doc->loadHTML($content);

                $content = $this->applyLinkTemplate($doc);
            }
        } 
    }

    /**
     * Alter and return the links in an accessible format
     *
     * @param string $content
     * @return void
     */
    public function applyLinkTemplate($doc)
    {
        $template = Config::inst()->get(AccLinkShortcodeProvider::class, 'custom_template');
        if (!$template) {
            return $doc->saveHTML();
        }

        $tags = $doc->getElementsByTagName('a');
        foreach ($tags as $tag) {
            $attributeArr = [];

            if ($tag->hasAttributes()) {
                foreach ($tag->attributes as $attr) {
                    $attributeArr[$attr->nodeName] = $attr->nodeValue;
                }
            }

            $attributeArr['text'] = $tag->nodeValue;
            $attributeArr['type'] = $this->checkLinkType($attributeArr['href']);

            $attrData = ArrayData::create($attributeArr);
            $viewableData = ViewableData::create();
            $render = $viewableData->renderWith($template, $attrData);

            $newNode = $doc->createDocumentFragment();
            $newNode->appendXML($render->getValue());
            $tag->parentNode->replaceChild($newNode, $tag);
        }

        return $doc->saveHTML();
    }

    public function checkLinkType($url)
    {
        $currentDomain = Director::absoluteBaseURL();
        $downloadFileTypes = [
            '.pdf', '.doc', '.docx', '.mp4', '.mp3', '.exe'
        ];

        if (strpos($url, 'mailto:') !== false) {
            return 'Email';
        } else if (strpos($url, 'tel:') !== false) {
            return 'Phone';
        } else {
            if (strpos($url, 'http') === false) {
                $url = Director::absoluteURL($url);
            }

            if (strpos($url, $currentDomain) !== false) {
                if (strpos($url, '#') !== false) {
                    return 'Anchor';
                }

                foreach ($downloadFileTypes as $type) {
                    if (strpos($url, $type) !== false) {
                        return 'Download';
                    }
                }

                return 'Internal';
            } else {
                foreach ($downloadFileTypes as $type) {
                    if (strpos($url, $type) !== false) {
                        return 'Download';
                    }
                }

                return 'External';
            }
        }
    }
}
