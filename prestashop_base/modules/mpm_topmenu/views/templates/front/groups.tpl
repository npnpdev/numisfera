{if isset($groups) && $groups}
    <div class="subcat_menu_groups subcat_menu_groups_{$id_topmenu_column|escape:'htmlall':'UTF-8'}">
        {foreach $groups as $key => $group}
            <div class="group_item group_item_{$group['id_topmenu_group']|escape:'htmlall':'UTF-8'}">
                {*{$group['item_css']|escape:'htmlall':'UTF-8' nofilter}*}
                {if isset($group['description_before']) && $group['description_before']} {$group['description_before']|escape:'htmlall':'UTF-8' nofilter} {/if}
                {if isset($group['group']) && $group['group']} {$group['group']|escape:'htmlall':'UTF-8' nofilter} {/if}
                {if isset($group['description_after']) && $group['description_after']} {$group['description_after']|escape:'htmlall':'UTF-8' nofilter} {/if}
            </div>
        {/foreach}
    </div>
{/if}