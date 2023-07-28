<?php

namespace Iliain\Accessible\ShortcodeProviders;

use SilverStripe\View\HTML;
use SilverStripe\Assets\File;
use SilverStripe\Assets\Image;
use SilverStripe\View\ArrayData;
use Psr\SimpleCache\CacheInterface;
use SilverStripe\View\ViewableData;
use SilverStripe\View\Parsers\ShortcodeParser;
use SilverStripe\Assets\Shortcodes\ImageShortcodeProvider;
use SilverStripe\Core\Config\Config;

/**
 * Provides an accessible renderer for image shortcodes
 *
 * @package silverstripe
 * @subpackage silverstripe-accessible
 */
class AccImageShortcodeProvider extends ImageShortcodeProvider
{
    /**
     * Whitelisted attributes - added caption
     *
     * @var array
     */
    private static array $attribute_whitelist = [
        'alt',
        'class',
        'height',
        'loading',
        'src',
        'title',
        'width',
        'caption'
    ];

    /**
     * Replace"[image id=n]" shortcode with an image reference.
     * Permission checks will be enforced by the file routing itself.
     *
     * @param array $args Arguments passed to the parser
     * @param string $content Raw shortcode
     * @param ShortcodeParser $parser Parser
     * @param string $shortcode Name of shortcode used to register this handler
     * @param array $extra Extra arguments
     * @return string Result of the handled shortcode
     */
    public static function handle_shortcode($args, $content, $parser, $shortcode, $extra = [])
    {
        /** @var CacheInterface $cache */
        $cache = static::getCache();
        $cacheKey = static::getCacheKey($args, $content);
        $cachedMarkup = static::getCachedMarkup($cache, $cacheKey, $args);
        if ($cachedMarkup) {
            return $cachedMarkup;
        }

        // Find appropriate record, with fallback for error handlers
        $fileFound = true;
        $record = static::find_shortcode_record($args, $errorCode);
        if ($errorCode) {
            $fileFound = false;
            $record = static::find_error_record($errorCode);
        }
        if (!$record) {
            return null; // There were no suitable matches at all.
        }

        // Check if a resize is required
        $width = null;
        $height = null;
        $grant = static::getGrant($record);
        $src = $record->getURL($grant);
        if ($record instanceof Image) {
            $width = isset($args['width']) ? (int) $args['width'] : null;
            $height = isset($args['height']) ? (int) $args['height'] : null;
            $hasCustomDimensions = ($width && $height);
            if ($hasCustomDimensions && (($width != $record->getWidth()) || ($height != $record->getHeight()))) {
                $resized = $record->ResizedImage($width, $height);
                // Make sure that the resized image actually returns an image
                if ($resized) {
                    $src = $resized->getURL($grant);
                }
            }
        }

        // Determine whether loading="lazy" is set
        $args = self::updateLoadingValue($args, $width, $height);

        // Build the HTML tag
        $attrs = array_merge(
            // Set overrideable defaults ('alt' must be present regardless of contents)
            ['src' => '', 'alt' => ''],
            // Use all other shortcode arguments
            $args,
            // But enforce some values
            ['id' => '', 'src' => $src]
        );

        $attrs['alt'] = $record->AltText;
        $attrs['caption'] = $record->Caption;

        // If file was not found then use the Title value from static::find_error_record() for the alt attr
        if (!$fileFound) {
            $attrs['alt'] = $record->Title;
        }

        // Clean out any empty attributes (aside from alt) and anything not whitelisted
        $whitelist = static::config()->get('attribute_whitelist');
        $attrs = array_filter($attrs ?? [], function ($v, $k) use ($whitelist) {
            return in_array($k, $whitelist) && (strlen(trim($v ?? '')) || $k === 'alt');
        }, ARRAY_FILTER_USE_BOTH);

        // Custom template added by module
        $template = Config::inst()->get(static::class, 'custom_template');
        if ($template) {
            $attrData = ArrayData::create($attrs);
            $viewableData = ViewableData::create();
            $markup = $viewableData->renderWith($template, $attrData);
        } else {
            $markup = HTML::createTag('img', $attrs);
        }

        // cache it for future reference
        if ($fileFound) {
            $cache->set($cacheKey, [
                'markup' => $markup,
                'filename' => $record instanceof File ? $record->getFilename() : null,
                'hash' => $record instanceof File ? $record->getHash() : null,
            ]);
        }

        return $markup;
    }

    /**
     * Updated the loading attribute which is used to either lazy-load or eager-load images
     * Eager-load is the default browser behaviour so when eager loading is specified, the
     * loading attribute is omitted
     *
     * @param array $args
     * @param int|null $width
     * @param int|null $height
     * @return array
     */
    private static function updateLoadingValue(array $args, ?int $width, ?int $height): array
    {
        if (!Image::getLazyLoadingEnabled()) {
            return $args;
        }
        if (isset($args['loading']) && $args['loading'] == 'eager') {
            // per image override - unset the loading attribute unset to eager load (default browser behaviour)
            unset($args['loading']);
        } elseif ($width && $height) {
            // width and height must be present to prevent content shifting
            $args['loading'] = 'lazy';
        }
        return $args;
    }
}
