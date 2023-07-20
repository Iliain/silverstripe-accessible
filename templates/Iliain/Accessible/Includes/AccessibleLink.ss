<% if $LinkURL %>
    <a href="{$LinkURL}" {$IDAttr} {$ClassAttr} title="{$AccessibleDescription}" {$TargetAttr}>
        {$Title}
    <% if $AccessibleType = 'External' || $OpenInNewWindow %>
        <i class="fa-solid fa-up-right-from-square"></i>
    <% else_if $AccessibleType = 'Anchor' %>
        <i class="fa-solid fa-anchor"></i>
    <% else_if $AccessibleType = 'Download' %>
        <i class="fa-solid fa-cloud-arrow-down"></i>
    <% else_if $AccessibleType = 'Email' %>
        <i class="fa-solid fa-envelope"></i>
    <% else_if $AccessibleType = 'Phone' %>
        <i class="fa-solid fa-phone"></i>
    <% end_if %>
    </a>
<% end_if %>