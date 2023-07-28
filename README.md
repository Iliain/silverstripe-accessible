# Silverstripe Accessible

[![Latest Stable Version](https://poser.pugx.org/iliain/silverstripe-accessible/v)](https://packagist.org/packages/iliain/silverstripe-accessible) 
[![Total Downloads](https://poser.pugx.org/iliain/silverstripe-accessible/downloads)](https://packagist.org/packages/iliain/silverstripe-accessible) 
[![Latest Unstable Version](https://poser.pugx.org/iliain/silverstripe-accessible/v/unstable)](https://packagist.org/packages/iliain/silverstripe-accessible) 
[![License](https://poser.pugx.org/iliain/silverstripe-accessible/license)](https://packagist.org/packages/iliain/silverstripe-accessible) 
[![PHP Version Require](https://poser.pugx.org/iliain/silverstripe-accessible/require/php)](https://packagist.org/packages/iliain/silverstripe-accessible)


Provides a number of additions to Silverstripe to make it more accessible.

* Adds Caption and AltText fields to Images
    - Renders in WYSIWYGs and Templates
* Adds visual icons to WYSIWYG hyperlinks
* Adds visual icons and descriptive text to the Links provided by:
    - [sheadawson/silverstripe-linkable](https://github.com/sheadawson/silverstripe-linkable)
    - [gorricoe/silverstripe-link](https://github.com/gorriecoe/silverstripe-link) (recommended)

## Installation (with composer)

	composer require iliain/silverstripe-accessible

## Requirements

* PHP 7.4+ or 8.0+
* Silverstripe 4+ or 5+

## Documentation

* [Images](docs/en/Images.md)
* [Links](docs/en/Links.md)

## Example Config

Below is the default config, you can overwrite any of these settings in your own config.yml

```YAML
Iliain\Accessible\ShortcodeProviders\AccImageShortcodeProvider:
  custom_template: 'Iliain\\Accessible\\Shortcodes\\Image'        # (String|null) Custom template 
Iliain\Accessible\ShortcodeProviders\AccLinkShortcodeProvider:
  custom_template: 'Iliain\\Accessible\\Shortcodes\\Link'         # (String|null) Custom template 

```

## To Do

* Add options to display File sizes in both templates and WYSIWYGs
* Add descriptive text to WYSIWYG hyperlinks

