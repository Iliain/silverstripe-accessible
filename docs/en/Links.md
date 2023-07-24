# Links

This extension adds an additional template to render in an accessible format, which can be overwritten in your own theme. A link can be rendered by default with `$ButtonLink.Accessible`, or you can render your own template and make use of the listed functions

## Usage

```HTML
<!-- The getAccessible function will render the accessible template for you -->
{$ButtonLink.Accessible}

<!-- Or you can render it yourself -->
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

## Template Functions

### $AccessibleType

Returns the base accessible type of the link, which will be one of the following values:

- Internal
- External
- Download
- Phone
- Email

### $DynamicAccessibleType 

Returns one of the above types, but will dynamically determine if the link is an Anchor link or not based on the user's current page. Returns a value of "Anchor" if it is.

E.g. The returned type will be "Internal":
```
Current Page: http://mysite.com/about-us
Link: http://mysite.com/contact-us#contact-form
```

E.g. The returned type will be "Anchor":
```
Current Page: http://mysite.com/contact-us
Link: http://mysite.com/contact-us#contact-form
```

### $AccessibleDescription

Returns the accessible description of the link. The current format is designed to appear as follows:

```html
Internal Link: I am a link to the About Us page (opens in a new window)
```

 - Internal Link - The type of link returned by `$DynamicAccessibleType`
 - I am a link to the About Us page - The `$AccessibleText` field of the link
 - (opens in a new window) - Appended if the link opens in a new window

## PHP Functions

### updateAccessibleDescription

An extension hook to allow you to change the text/format of the `$AccessibleDescription`
