<picture>
    <source srcset="{$URL}" type="image/{$getExtension}">
    <img {$AttributesHTML} alt="{$AltText}">
    <% if $Caption %>
        <figcaption>{$Caption}</figcaption>
    <% end_if %>
</picture>
