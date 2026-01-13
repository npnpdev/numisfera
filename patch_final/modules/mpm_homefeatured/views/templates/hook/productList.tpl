<div >
    {if $items}
        <table class="table_product_list_home">
            <thead>
                <tr>
                    <th class="table_id">{l s='ID' mod='mpm_homefeatured'}</th>
                    <th class="table_img">{l s='Image' mod='mpm_homefeatured'}</th>
                    <th class="table_name">{l s='Name' mod='mpm_homefeatured'}</th>
                    <th class="table_delete">{l s='Delete' mod='mpm_homefeatured'}</th>
                </tr>
            </thead>
            <tbody>
            {foreach  from=$items key=key item=item}
                <tr class="item_product item_product_{$item['id_product']|escape:'htmlall':'UTF-8'} {if $key % 2 == 0} odd{/if}" data-id-product="{$item['id_product']|escape:'htmlall':'UTF-8'}">
                    <td class="table_id">{$item['id_product']|escape:'htmlall':'UTF-8'}</td>
                    <td class="table_img"><img class="img_product_mativator" src="{$item['image']|escape:'htmlall':'UTF-8'}"></td>
                    <td class="table_name">{$item['name']|escape:'htmlall':'UTF-8'}</td>
                    <td class="table_delete"><a class="btn btn-default" data-id-product="{$item['id_product']|escape:'htmlall':'UTF-8'}"><i class="icon-trash"></i></a></td>
                </tr>
            {/foreach}
            </tbody>
        </table>
        <div style="clear: both"></div>
    {else}
        <div class="alert alert-warning">{l s='You have not added any product' mod='mpm_homefeatured'}</div>
    {/if}
</div>