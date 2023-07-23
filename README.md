# Silverstripe Accessible

Provides a number of additions to Silverstripe to make it more accessible.

* Adds Caption and AltText fields to Images
* Adds accessibility features to the Links provided by [sheadawson/silverstripe-linkable](https://github.com/sheadawson/silverstripe-linkable) and [gorricoe/silverstripe-link](https://github.com/gorriecoe/silverstripe-link)

## Installation (with composer)

	composer require iliain/silverstripe-accessible

## Requirements

* PHP 7.4+ or 8.0+
* Silverstripe 4+ or 5+

## Usage

### Images

Adds a pair of new fields to Images that can be edited in the CMS, Caption and AltText. These fields can either be rendered manually, or with the provided template.

```HTML
<!-- The getAccessible function will render the accessible template for you -->
{$Image.Accessible}

<% with $Image %>
    <img src="{$LinkURL}" alt="{$AltText}">
    <p>{$Caption}</p>
<% end_with>
```

You can overwrite the template in `templates/Iliain/Accessibility/Includes/AccessibleImage.ss`

### Links
This extension adds an additional template to render in an accessible format, which can be overwritten in your own theme.

```HTML
<!-- The getAccessible function will render the accessible template for you -->
{$ButtonLink.Accessible}

<% with $ButtonLink %>
    <a href="{$LinkURL}" title="{$AccessibleDescription}">{$Title}</a>
<% end_with %>
```

The current implementation uses FontAwesome to render icons, but you can change this by overwriting the template in `templates/Iliain/Accessibility/Includes/AccessibleLink.ss`

An example of a Link's output:

```HTML
<a href="/about-us" title="Internal Link: I am a link to the About Us page (opens in a new window)" target="_blank" rel="noopener">
    About Us
    <i class="fa-solid fa-up-right-from-square"></i>
</a>
```
