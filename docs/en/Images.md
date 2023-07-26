# Images

Adds a pair of new fields to Images that can be edited in the CMS, Caption and AltText. These fields can either be rendered manually, or with the provided template.

## Usage

### Template Files
```HTML
<!-- The getAccessible function will render the accessible template for you -->
{$Image.Accessible}

<!-- Or you can render it yourself -->
<% with $Image %>
    <img src="{$LinkURL}" alt="{$AltText}">
    <p>{$Caption}</p>
<% end_with>
```

You can overwrite the template in `templates/Iliain/Accessibility/Includes/AccessibleImage.ss`

### WYSIWYG

You can change the WYSIWYG template with the following config, as well as enable or disable the feature:

```YAML
Iliain\Accessible\Config:
    settings:
      enable_image_shortcode: true # enable or disable the shortcode extensions
    customise:
      image_shortcode_template: 'Iliain\Accessible\Includes\AccessibleShortcodeImage' # this is the default template
```

## Template Functions

### $AltText

Returns the AltText field of the image

### $Caption 

Returns the Caption field of the image
