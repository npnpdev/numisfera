<label class="control-label col-lg-3"> {l s='Links list' mod='mpm_topmenu'}  </label>
<div class="added_links col-lg-9">
    <div class="form-wrapper">
        <div class="form-group form-group-link-add-left">
            <table class="table_link_list">
                <thead>
                <tr>
                    <th class="table_link_id">{l s='ID' mod='mpm_topmenu'}</th>
                    <th class="table_link_name">{l s='Title' mod='mpm_topmenu'}</th>
                    <th class="table_link_url">{l s='Url' mod='mpm_topmenu'}</th>
                    <th class="table_link_edit">{l s='Edit' mod='mpm_topmenu'}</th>
                    <th class="table_link_delete">{l s='Delete' mod='mpm_topmenu'}</th>
                </tr>
                </thead>
                <tbody>
                {if isset($items) && $items}
                    {foreach  from=$items key=key item=item}
                        <tr class="item_link_{$item['id']|escape:'htmlall':'UTF-8'} {if $key % 2 == 0} odd{/if} row_{$item['id']|escape:'htmlall':'UTF-8'}" data-id-link="{$item['id']|escape:'htmlall':'UTF-8'}">
                            <td class="table_link_id">{$item['id']|escape:'htmlall':'UTF-8'}</td>
                            <td class="table_link_name">{$item['title']|escape:'htmlall':'UTF-8'}</td>
                            <td class="table_link_url">{$item['url']|escape:'htmlall':'UTF-8'}</td>
                            <td class="table_link_edit"><a class="btn btn-default" data-id-link="{$item['id']|escape:'htmlall':'UTF-8'}"><i class="icon-pencil"></i></a></td>
                            <td class="table_link_delete"><a class="btn btn-default" data-id-link="{$item['id']|escape:'htmlall':'UTF-8'}"><i class="icon-trash"></i></a></td>
                        </tr>
                    {/foreach}
                {else}
                    <tr>
                        <td class="list-empty" colspan="5">
                            <div class="list-empty-msg">
                                <i class="icon-warning-sign list-empty-icon"></i>
                                <h4>{l s='No links ' mod='mpm_topmenu'}</h4>
                            </div>
                        </td>
                    </tr>
                {/if}
                </tbody>
            </table>
            <div style="clear: both"></div>
        </div>
    </div>

</div>