# Images

Adds a pair of new fields to Images that can be edited in the CMS: Caption and AltText. These fields can either be rendered manually, or with the provided template.

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

You can change the WYSIWYG template with the following config. Setting it to null will disable the feature

```YAML
Iliain\Accessible\ShortcodeProviders\AccImageShortcodeProvider:
  custom_template: 'Iliain\\Accessible\\Shortcodes\\Image'        # (String|null) Custom template 
```

## Example

This is an example of the base template, specifically from the WYSIWYG

```HTML
  <figure>
      <img src="/assets/image.png" class="center ss-htmleditorfield-file image" alt="I am alt text" width="388" height="388" loading="lazy" title="This is the tooltip">
      <figcaption>I am a caption look at me</figcaption>
  </figure>
```

## Template Functions

### $AltText

Returns the AltText field of the image

### $Caption 

Returns the Caption field of the image
