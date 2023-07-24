<% if $LinkURL %>
    <a href="{$LinkURL}" {$IDAttr} {$ClassAttr} title="{$AccessibleDescription}" {$TargetAttr}>
        {$Title}
    <% if $DynamicAccessibleType = 'External' || $OpenInNewWindow %>
        <i class="fa-solid fa-up-right-from-square"></i>
    <% else_if $DynamicAccessibleType = 'Anchor' %>
        <i class="fa-solid fa-anchor"></i>
    <% else_if $DynamicAccessibleType = 'Download' %>
        <i class="fa-solid fa-cloud-arrow-down"></i>
    <% else_if $DynamicAccessibleType = 'Email' %>
        <i class="fa-solid fa-envelope"></i>
    <% else_if $DynamicAccessibleType = 'Phone' %>
        <i class="fa-solid fa-phone"></i>
    <% end_if %>
    </a>
<% end_if %>