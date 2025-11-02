<ul class="tabs_group">
    {foreach $columns as $key => $column}
        <li data-id="{$column['id_topmenu_column']|escape:'htmlall':'UTF-8'}" class="tab_group_item {if $id_topmenu_column == $column['id_topmenu_column']} active{/if}">{$column['title']|escape:'htmlall':'UTF-8'}</li>
    {/foreach}
</ul>