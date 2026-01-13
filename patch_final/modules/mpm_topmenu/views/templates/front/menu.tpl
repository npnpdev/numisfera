{if isset($menu_items) && $menu_items}
    <div class="topMenuBlock desktop">
        <ul class="topmenu">
            {foreach $menu_items as $key => $value}
                <li data-id="{$value['id_topmenu']|escape:'htmlall':'UTF-8'}" class="item_menu item_menu_{$value['id_topmenu']|escape:'htmlall':'UTF-8'} {if $value['width']>0} narrow_item{/if}">
                    {*{$value['item_css']|escape:'htmlall':'UTF-8' nofilter}*}
                    <a class="item_menu_link" {if isset($value['open_new_window']) && $value['open_new_window']} target="_blank" {/if}  {if isset($value['link']) && $value['link']} href="{$value['link']|escape:'htmlall':'UTF-8'}" {/if}>{$value['title']|escape:'htmlall':'UTF-8'}</a>
                    {if $value['columns'] || $value['description_before'] || $value['description_after']}
                        <div class="subcat_menu subcat_menu_{$value['id_topmenu']|escape:'htmlall':'UTF-8'}">
                            {if isset($value['description_before']) && $value['description_before']} {$value['description_before']|escape:'htmlall':'UTF-8' nofilter} {/if}
                            {if isset($value['columns']) && $value['columns']}{$value['columns']|escape:'htmlall':'UTF-8' nofilter} {/if}
                            {if isset($value['description_after']) && $value['description_after']} {$value['description_after']|escape:'htmlall':'UTF-8' nofilter} {/if}
                        </div>

                        <div class="categories-block-arrows">
                            <span class="column-arrows-add active"><i class="material-icons">add</i></span>
                            <span class="column-arrows-remove"><i class="material-icons">remove</i></span>
                        </div>

                    {/if}
                </li>
            {/foreach}
        </ul>
        <div onclick="" class="topmenu_mobile"><i class="material-icons">menu</i></div>
    </div>
{/if}