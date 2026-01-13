
    <label class="control-label col-lg-3">{if isset($label) && $label}{$label|escape:'htmlall':'UTF-8'}{/if}</label>
    <div class="col-lg-9">
        <table class="table_item {if isset($class) && $class}{$class|escape:'htmlall':'UTF-8'}{/if}">
            <thead>
                <tr>
                    {if isset($value[0]['id']) && $value[0]['id']}<th class="table_item_id">{l s='ID' mod='mpm_topmenu'}</th>{/if}
                    <th class="table_item_val">{l s='Selected' mod='mpm_topmenu'}</th>
                    <th class="table_item_name">{l s='Name' mod='mpm_topmenu'}</th>
                </tr>
            </thead>
            <tbody>
                {if isset($value) && $value}
                    {foreach $value as $key => $val}
                        <tr class="{if $key % 2 == 0} odd{/if}">
                            {if isset($val['id']) && $val['id']}<td class="table_item_id">{$val['id']|escape:'htmlall':'UTF-8'}</td>{/if}
                            <td class="table_item_val"><input type="checkbox" {if $val['checked']}checked{/if} value="{$val['value']|escape:'htmlall':'UTF-8'}" name="{$val['name']|escape:'htmlall':'UTF-8'}"></td>
                            <td class="table_item_name">{$val['title']|escape:'htmlall':'UTF-8'}</td>
                        </tr>
                    {/foreach}
                {else}
                    <tr>
                        <td class="list-empty" colspan="{if isset($val['id']) && $val['id']}3{else}2{/if}">
                            <div class="list-empty-msg">
                                <i class="icon-warning-sign list-empty-icon"></i>
                                <h4>{l s='No items ' mod='mpm_topmenu'}</h4>
                            </div>
                        </td>
                    </tr>
                {/if}
            </tbody>
        </table>
    </div>




