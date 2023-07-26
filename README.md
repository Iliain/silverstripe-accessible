# Silverstripe Accessible

[![Latest Stable Version](https://poser.pugx.org/iliain/silverstripe-accessible/v)](https://packagist.org/packages/iliain/silverstripe-accessible) 
[![Total Downloads](https://poser.pugx.org/iliain/silverstripe-accessible/downloads)](https://packagist.org/packages/iliain/silverstripe-accessible) 
[![Latest Unstable Version](https://poser.pugx.org/iliain/silverstripe-accessible/v/unstable)](https://packagist.org/packages/iliain/silverstripe-accessible) 
[![License](https://poser.pugx.org/iliain/silverstripe-accessible/license)](https://packagist.org/packages/iliain/silverstripe-accessible) 
[![PHP Version Require](https://poser.pugx.org/iliain/silverstripe-accessible/require/php)](https://packagist.org/packages/iliain/silverstripe-accessible)


Provides a number of additions to Silverstripe to make it more accessible.

* Adds Caption and AltText fields to Images (both in templates and in WYSIWYGs)
* Adds accessibility features to the Links provided by [sheadawson/silverstripe-linkable](https://github.com/sheadawson/silverstripe-linkable) and [gorricoe/silverstripe-link](https://github.com/gorriecoe/silverstripe-link)

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
Iliain\Accessible\Config:
    settings:
      enable_image_shortcode: true # (Boolean) Enable the image shortcode feature
      enable_link_shortcode: true # (Boolean) Enable the link shortcode feature
    customise:
      image_shortcode_template: 'Iliain\Accessible\Includes\AccessibleShortcodeImage' # (String) Template to use for the image shortcode
      link_shortcode_template: 'Iliain\Accessible\Includes\AccessibleShortcodeLink' # (String) Template to use for the link shortcode
```

## To Do

* Include link features in WYSIWYGs
* Add options to display File sizes in both templates and WYSIWYGs

