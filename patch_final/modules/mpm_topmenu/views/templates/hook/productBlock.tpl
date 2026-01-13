
<div class="form-group">
    <label class="control-label col-lg-3">{l s='Show product name' mod='mpm_topmenu'}</label>
    <div class="col-lg-9">
            <span class="switch prestashop-switch fixed-width-lg">
                <input type="radio" name="product_title_{$ident|escape:'htmlall':'UTF-8'}" id="product_title_{$ident|escape:'htmlall':'UTF-8'}_on" value="1" {if isset($product_title) && $product_title}checked="checked"{/if}>
                <label for="product_title_{$ident|escape:'htmlall':'UTF-8'}_on">{l s='Yes' mod='mpm_topmenu'}</label>
                <input type="radio" name="product_title_{$ident|escape:'htmlall':'UTF-8'}" id="product_title_{$ident|escape:'htmlall':'UTF-8'}_off" value="0" {if !isset($product_title) || !$product_title}checked="checked"{/if}>
                <label for="product_title_{$ident|escape:'htmlall':'UTF-8'}_off">{l s='No' mod='mpm_topmenu'}</label>
                <a class="slide-button btn"></a>
			</span>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-lg-3">{l s='Show product image' mod='mpm_topmenu'}</label>
    <div class="col-lg-9">
            <span class="switch prestashop-switch fixed-width-lg">
                <input type="radio" name="product_img_{$ident|escape:'htmlall':'UTF-8'}" id="product_img_{$ident|escape:'htmlall':'UTF-8'}_on" value="1" {if isset($product_img) && $product_img}checked="checked"{/if}>
                <label for="product_img_{$ident|escape:'htmlall':'UTF-8'}_on">{l s='Yes' mod='mpm_topmenu'}</label>
                <input type="radio" name="product_img_{$ident|escape:'htmlall':'UTF-8'}" id="product_img_{$ident|escape:'htmlall':'UTF-8'}_off" value="0" {if !isset($product_img) || !$product_img}checked="checked"{/if}>
                <label for="product_img_{$ident|escape:'htmlall':'UTF-8'}_off">{l s='No' mod='mpm_topmenu'}</label>
                <a class="slide-button btn"></a>
			</span>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-lg-3">{l s='Show product price' mod='mpm_topmenu'}</label>
    <div class="col-lg-9">
            <span class="switch prestashop-switch fixed-width-lg">
                <input type="radio" name="product_price_{$ident|escape:'htmlall':'UTF-8'}" id="product_price_{$ident|escape:'htmlall':'UTF-8'}_on" value="1" {if isset($product_price) && $product_price}checked="checked"{/if}>
                <label for="product_price_{$ident|escape:'htmlall':'UTF-8'}_on">{l s='Yes' mod='mpm_topmenu'}</label>
                <input type="radio" name="product_price_{$ident|escape:'htmlall':'UTF-8'}" id="product_price_{$ident|escape:'htmlall':'UTF-8'}_off" value="0" {if !isset($product_price) || !$product_price}checked="checked"{/if}>
                <label for="product_price_{$ident|escape:'htmlall':'UTF-8'}_off">{l s='No' mod='mpm_topmenu'}</label>
                <a class="slide-button btn"></a>
			</span>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-lg-3">{l s='Show button add to cart' mod='mpm_topmenu'}</label>
    <div class="col-lg-9">
            <span class="switch prestashop-switch fixed-width-lg">
                <input type="radio" name="product_add_{$ident|escape:'htmlall':'UTF-8'}" id="product_add_{$ident|escape:'htmlall':'UTF-8'}_on" value="1" {if isset($product_add) && $product_add}checked="checked"{/if}>
                <label for="product_add_{$ident|escape:'htmlall':'UTF-8'}_on">{l s='Yes' mod='mpm_topmenu'}</label>
                <input type="radio" name="product_add_{$ident|escape:'htmlall':'UTF-8'}" id="product_add_{$ident|escape:'htmlall':'UTF-8'}_off" value="0" {if !isset($product_add) || !$product_add}checked="checked"{/if}>
                <label for="product_add_{$ident|escape:'htmlall':'UTF-8'}_off">{l s='No' mod='mpm_topmenu'}</label>
                <a class="slide-button btn"></a>
			</span>
    </div>
</div>
<div class="form-group block_settings_number">
    <label class="control-label col-lg-3">{l s='Type image' mod='mpm_topmenu'} </label>
    <div class="col-lg-9">
        <select class="type_image_{$ident|escape:'htmlall':'UTF-8'}" name="type_image_{$ident|escape:'htmlall':'UTF-8'}" >
            {foreach $type_img_all as $value}
                <option {if isset($type_img_select) && $type_img_select && ($type_img_select == $value['name'])}selected="selected"{/if} value="{$value['name']|escape:'htmlall':'UTF-8'}">{$value['name']|escape:'htmlall':'UTF-8'}</option>
            {/foreach}
        </select>
    </div>
</div>




<label class="control-label col-lg-3"> {l s='Add product' mod='mpm_topmenu'} </label>
<div class="col-lg-9">
    <div class="form-group">
        <div id="control-label col-lg-3">
            <div style="float: left">
                <input class="attendee" id="attendee" name="AttendeeId" type="text" value="" placeholder="{l s='Search for a product' mod='mpm_topmenu'}"/>

            </div>
            <div class="col-lg-2 product-pack-button">
                <button type="button" id="add_products_item" class="btn btn-default">
                    <i class="icon-plus-sign-alt"></i> {l s='Add this product' mod='mpm_topmenu'}
                </button>
                <input id="productIds" name="productIds"  type="hidden" value="{if isset($ids) && $ids}{$ids|escape:'htmlall':'UTF-8'}{/if}" />

            </div>
        </div>
    </div>
</div>
<label class="control-label col-lg-3"> {l s='Added products' mod='mpm_topmenu'}  </label>
<div class="col-lg-9 added_products">
    <div class="form-wrapper">
        <div class="form-group form-group-products-add-left">
            <table class="table_product_list">
                <thead>
                    <tr>
                        <th class="table_list_id">{l s='ID' mod='mpm_topmenu'}</th>
                        <th class="table_list_img">{l s='Image' mod='mpm_topmenu'}</th>
                        <th class="table_prod_name">{l s='Name' mod='mpm_topmenu'}</th>
                        <th class="table_list_delete">{l s='Delete' mod='mpm_topmenu'}</th>
                    </tr>
                </thead>
                <tbody>
                {if isset($items) && $items}
                    {foreach  from=$items key=key item=item}
                        <tr class="item_product item_product_{$item['id_product']|escape:'htmlall':'UTF-8'} {if $key % 2 == 0} odd{/if} row_{$item['id_product']|escape:'htmlall':'UTF-8'}" data-id-product="{$item['id_product']|escape:'htmlall':'UTF-8'}">
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
                </tbody>
            </table>
            <div style="clear: both"></div>
        </div>
    </div>

</div>