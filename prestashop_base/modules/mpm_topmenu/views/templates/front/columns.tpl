{if isset($columns) && $columns}
    <table class="subcat_menu_table subcat_menu_table_{$id_topmenu|escape:'htmlall':'UTF-8'}">
        <tbody>
            <tr >
                {foreach $columns as $key => $column}
                    <td class="column_item column_item_{$column['id_topmenu_column']|escape:'htmlall':'UTF-8'}">
                        {*{$column['item_css']|escape:'htmlall':'UTF-8' nofilter}*}
                        {if isset($column['description_before']) && $column['description_before']} {$column['description_before']|escape:'htmlall':'UTF-8' nofilter} {/if}
                        {if isset($column['groups']) && $column['groups']} {$column['groups']|escape:'htmlall':'UTF-8' nofilter} {/if}
                        {if isset($column['description_after']) && $column['description_after']} {$column['description_after']|escape:'htmlall':'UTF-8' nofilter} {/if}
                    </td>
                {/foreach}
            </tr>
            </tbody>
    </table>
{/if}

