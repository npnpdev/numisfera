{if isset($items) && $items}
    {foreach  from=$items key=key item=item}
        <tr class="{if $key % 2 == 0} odd{/if} row_{$item['id_product']|escape:'htmlall':'UTF-8'}" data-id-product="{$item['id_product']|escape:'htmlall':'UTF-8'}">
            <td class="table_list_id">{$item['id_product']|escape:'htmlall':'UTF-8'}</td>
            <td class="table_list_img"><img class="img_product_mativator" src="{$item['image']|escape:'htmlall':'UTF-8'}"></td>
            <td class="table_list_name">{$item['name']|escape:'htmlall':'UTF-8'}</td>
            <td class="table_list_delete"><a class="btn btn-default" data-id-product="{$item['id_product']|escape:'htmlall':'UTF-8'}"><i class="icon-trash"></i></a></td>
        </tr>
    {/foreach}
{else}
    <tr>
        <td class="list-empty" colspan="4">
            <div class="list-empty-msg">
                <i class="icon-warning-sign list-empty-icon"></i>
                <h4>{l s='No selected products ' mod='mpm_topmenu'}</h4>
            </div>
        </td>
    </tr>
{/if}


