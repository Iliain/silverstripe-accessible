<?php

use SilverStripe\View\Parsers\ShortcodeParser;
use Iliain\Accessible\ShortcodeProviders\AccImageShortcodeProvider;

$defaultParser = ShortcodeParser::get('default');

// Update image shortcode
$defaultParser->unregister('image');
$defaultParser->register('image', [AccImageShortcodeProvider::class, 'handle_shortcode']);
