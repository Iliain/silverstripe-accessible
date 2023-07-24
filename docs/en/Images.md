# Images

An image can be rendered by default with `$Image.Accessible`, or you can render your own template and make use of the listed functions

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

## Template Functions

### $AltText

Returns the AltText field of the image

### $Caption 

Returns the Caption field of the image
