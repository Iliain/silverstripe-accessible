<a href="{$href}" title="{$title}" <% if $target %>target="{$target}"<% end_if %>>{$text}
    <% if $type = 'External' || $target %>
        <i class="fa-solid fa-up-right-from-square"></i>
    <% else_if $type = 'Anchor' %>
        <i class="fa-solid fa-anchor"></i>
    <% else_if $type = 'Download' %>
        <i class="fa-solid fa-cloud-arrow-down"></i>
    <% else_if $type = 'Email' %>
        <i class="fa-solid fa-envelope"></i>
    <% else_if $type = 'Phone' %>
        <i class="fa-solid fa-phone"></i>
    <% end_if %>
</a>
