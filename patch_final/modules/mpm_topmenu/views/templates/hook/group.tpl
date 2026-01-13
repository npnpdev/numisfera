<div data-id="{$ident|escape:'htmlall':'UTF-8'}" class="block_item_group block_item_group_{$ident|escape:'htmlall':'UTF-8'}">

    <div class="button_block_group button_block_group_{$ident|escape:'htmlall':'UTF-8'}">
        <a data-id="{$ident|escape:'htmlall':'UTF-8'}" class="position_column"><i class="material-icons">open_with</i></a>
        <label class="button_block_label">Group_{$ident|escape:'htmlall':'UTF-8'}</label>

        <div class="button_item">
            <button data-id="{$ident|escape:'htmlall':'UTF-8'}" type="button" class="btn btn-default add_column"><i class="material-icons">add_circle_outline</i><span>{l s='Add new group' mod='mpm_topmenu'}</span></button>
            <button data-id="{$ident|escape:'htmlall':'UTF-8'}" type="button" class="btn btn-default save_column"><i class="material-icons">save</i><span>{l s='Save group' mod='mpm_topmenu'}</span></button>
            <button data-id="{$ident|escape:'htmlall':'UTF-8'}" type="button" class="btn btn-default open_column"><i class="material-icons">mode_edit</i><span>{l s='Edit group' mod='mpm_topmenu'}</span></button>
            <button data-id="{$ident|escape:'htmlall':'UTF-8'}" type="button" class="btn btn-default remove_column"><i class="material-icons">delete</i><span>{l s='Remove group' mod='mpm_topmenu'}</span></button>
            <input type="hidden" name="id_group_{$ident|escape:'htmlall':'UTF-8'}" class="id_group">
            <input type="hidden" name="current_column" class="current_column" value="{$id_topmenu_column|escape:'htmlall':'UTF-8'}">
            <input type="hidden" name="id_group" class="id_group" value="0">

        </div>
    </div>

    <div class="form-group form_group_class_top_line form_group_class_{$ident|escape:'htmlall':'UTF-8'} hide">
        <label class="control-label col-lg-3">{l s='Active' mod='mpm_topmenu'}</label>
        <div class="col-lg-9">
            <span class="switch prestashop-switch fixed-width-lg">
                <input type="radio" name="active_group_{$ident|escape:'htmlall':'UTF-8'}" id="active_group_{$ident|escape:'htmlall':'UTF-8'}_on" value="1">
                <label for="active_group_{$ident|escape:'htmlall':'UTF-8'}_on">{l s='Yes' mod='mpm_topmenu'}</label>
                <input type="radio" name="active_group_{$ident|escape:'htmlall':'UTF-8'}" id="active_group_{$ident|escape:'htmlall':'UTF-8'}_off" value="0" checked="checked">
                <label for="active_group_{$ident|escape:'htmlall':'UTF-8'}_off">{l s='No' mod='mpm_topmenu'}</label>
                <a class="slide-button btn"></a>
			</span>
        </div>
    </div>

    <div class="form-group block_settings_number form_group_class_{$ident|escape:'htmlall':'UTF-8'} hide">
        <label class="control-label col-lg-3">{l s=' Title group ' mod='mpm_topmenu'} </label>
        <div class="col-lg-9">
            <input type="text" name="title_group_{$ident|escape:'htmlall':'UTF-8'}" class="title_group_{$ident|escape:'htmlall':'UTF-8'}" value="Group_{$ident|escape:'htmlall':'UTF-8'}">
            <p class="help-block">{l s='(is not displayed in front office)' mod='mpm_topmenu'} </p>
        </div>
    </div>

    <div class="form-group form-group-title form_group_class_{$ident|escape:'htmlall':'UTF-8'} hide">
        <label class="control-label col-lg-3">{l s=' Title ' mod='mpm_topmenu'} </label>
        <div class="col-lg-9">
                {if count($languages) > 1}
                    <div class="form-group">
                        {foreach $languages as $language}
                            {if count($languages) > 1}
                                <div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                                <div class="col-lg-9">
                            {/if}

                            <input type="text" id="titleitem_{$language.id_lang|escape:'htmlall':'UTF-8'}"  name="titleitem_{$language.id_lang|escape:'htmlall':'UTF-8'}"  />

                            {if count($languages) > 1}
                                </div>
                                    <div class="col-lg-2">
                                        <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                            {$language.iso_code|escape:'htmlall':'UTF-8'}
                                            <i class="icon-caret-down"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            {foreach from=$languages item=language}
                                                <li><a href="javascript:hideOtherLanguage({$language.id_lang|escape:'htmlall':'UTF-8'});" tabindex="-1">{$language.name|escape:'htmlall':'UTF-8'}</a></li>
                                            {/foreach}
                                        </ul>
                                    </div>
                                </div>
                            {/if}
                        {/foreach}
                    </div>
                {else}
                    <input type="text" id="titleitem_{$defaultFormLanguage|escape:'htmlall':'UTF-8'}"  name="titleitem_{$defaultFormLanguage|escape:'htmlall':'UTF-8'}"  />
                {/if}
            <p class="help-block">{l s='(is displayed in front office)' mod='mpm_topmenu'} </p>
        </div>
    </div>



    <div class="form-group block_settings_number form_group_class_{$ident|escape:'htmlall':'UTF-8'} hide">
        <label class="control-label col-lg-3">{l s='Type ' mod='mpm_topmenu'} </label>
        <div class="col-lg-9">
            <select data-id="{$ident|escape:'htmlall':'UTF-8'}" class="type" name="type_{$ident|escape:'htmlall':'UTF-8'}" >
                <option value="product">{l s='Products' mod='mpm_topmenu'}</option>
                <option value="category">{l s='Categories' mod='mpm_topmenu'}</option>
                <option value="cms">{l s='Cms' mod='mpm_topmenu'}</option>
                <option value="link">{l s='Link' mod='mpm_topmenu'}</option>
                <option value="brand">{l s='Brands' mod='mpm_topmenu'}</option>
                <option value="supplier">{l s='Suppliers' mod='mpm_topmenu'}</option>
                <option value="page">{l s='Pages' mod='mpm_topmenu'}</option>
                <option value="image">{l s='Image' mod='mpm_topmenu'}</option>
                <option value="description">{l s='Description' mod='mpm_topmenu'}</option>
            </select>
        </div>
    </div>

    <div class="form-group form_group_type_content form_group_class_{$ident|escape:'htmlall':'UTF-8'} hide">

        <div class="form-group">
            <label class="control-label col-lg-3">{l s='Show product name' mod='mpm_topmenu'}</label>
            <div class="col-lg-9">
            <span class="switch prestashop-switch fixed-width-lg">
                <input type="radio" name="product_title_{$ident|escape:'htmlall':'UTF-8'}" id="product_{$ident|escape:'htmlall':'UTF-8'}_title_on" value="1" checked="checked">
                <label for="product_title_{$ident|escape:'htmlall':'UTF-8'}_on">{l s='Yes' mod='mpm_topmenu'}</label>
                <input type="radio" name="product_title_{$ident|escape:'htmlall':'UTF-8'}" id="product_title_{$ident|escape:'htmlall':'UTF-8'}_off" value="0" >
                <label for="product_title_{$ident|escape:'htmlall':'UTF-8'}_off">{l s='No' mod='mpm_topmenu'}</label>
                <a class="slide-button btn"></a>
			</span>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">{l s='Show product image' mod='mpm_topmenu'}</label>
            <div class="col-lg-9">
            <span class="switch prestashop-switch fixed-width-lg">
                <input type="radio" name="product_img_{$ident|escape:'htmlall':'UTF-8'}" id="product_img_{$ident|escape:'htmlall':'UTF-8'}_on" value="1" checked="checked">
                <label for="product_img_{$ident|escape:'htmlall':'UTF-8'}_on">{l s='Yes' mod='mpm_topmenu'}</label>
                <input type="radio" name="product_img_{$ident|escape:'htmlall':'UTF-8'}" id="product_img_{$ident|escape:'htmlall':'UTF-8'}_off" value="0" >
                <label for="product_img_{$ident|escape:'htmlall':'UTF-8'}_off">{l s='No' mod='mpm_topmenu'}</label>
                <a class="slide-button btn"></a>
			</span>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">{l s='Show product price' mod='mpm_topmenu'}</label>
            <div class="col-lg-9">
            <span class="switch prestashop-switch fixed-width-lg">
                <input type="radio" name="product_price_{$ident|escape:'htmlall':'UTF-8'}" id="product_price_{$ident|escape:'htmlall':'UTF-8'}_on" value="1" checked="checked">
                <label for="product_price_{$ident|escape:'htmlall':'UTF-8'}_on">{l s='Yes' mod='mpm_topmenu'}</label>
                <input type="radio" name="product_price_{$ident|escape:'htmlall':'UTF-8'}" id="product_price_{$ident|escape:'htmlall':'UTF-8'}_off" value="0" >
                <label for="product_price_{$ident|escape:'htmlall':'UTF-8'}_off">{l s='No' mod='mpm_topmenu'}</label>
                <a class="slide-button btn"></a>
			</span>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">{l s='Show button add to cart' mod='mpm_topmenu'}</label>
            <div class="col-lg-9">
            <span class="switch prestashop-switch fixed-width-lg">
                <input type="radio" name="product_add_{$ident|escape:'htmlall':'UTF-8'}" id="product_add_{$ident|escape:'htmlall':'UTF-8'}_on" value="1" checked="checked">
                <label for="product_add_{$ident|escape:'htmlall':'UTF-8'}_on">{l s='Yes' mod='mpm_topmenu'}</label>
                <input type="radio" name="product_add_{$ident|escape:'htmlall':'UTF-8'}" id="product_add_{$ident|escape:'htmlall':'UTF-8'}_off" value="0" >
                <label for="product_add_{$ident|escape:'htmlall':'UTF-8'}_off">{l s='No' mod='mpm_topmenu'}</label>
                <a class="slide-button btn"></a>
			</span>
            </div>
        </div>
        <div class="form-group block_settings_number">
            <label class="control-label col-lg-3">{l s='Type image' mod='mpm_topmenu'} </label>
            <div class="col-lg-9">
                <select class="type_image_{$ident|escape:'htmlall':'UTF-8'}" name="type_image_{$ident|escape:'htmlall':'UTF-8'}" >
                    {foreach $type_img as $value}
                        <option value="{$value['name']|escape:'htmlall':'UTF-8'}">{$value['name']|escape:'htmlall':'UTF-8'}</option>
                    {/foreach}
                </select>
            </div>
        </div>




        <label class="control-label col-lg-3"> {l s='Add product' mod='mpm_topmenu'}  </label>
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
        <div class="added_products col-lg-9">
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
    </div>

    <div class="form-group form_group_class_{$ident|escape:'htmlall':'UTF-8'} hide">
        <label class="control-label col-lg-3">
            <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title=" ">{l s='Background color' mod='mpm_topmenu'}</span>
        </label>
        <div class="col-lg-9">
            <div class="form-group">
                <div class="col-lg-2">
                    <div class="row">
                        <div class="input-group">
                            <input type="color" data-hex="true" class="color mColorPickerInput"  name="background_color_group_{$ident|escape:'htmlall':'UTF-8'}" value="#ffffff" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="form-group form_group_class_{$ident|escape:'htmlall':'UTF-8'} hide">
        <label class="control-label col-lg-3">
            <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title=" ">{l s='Text color' mod='mpm_topmenu'}</span>
        </label>
        <div class="col-lg-9">
            <div class="form-group">
                <div class="col-lg-2">
                    <div class="row">
                        <div class="input-group">
                            <input type="color" data-hex="true" class="color mColorPickerInput"  name="text_color_group_{$ident|escape:'htmlall':'UTF-8'}" value="#000000" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="form-group form_group_class_{$ident|escape:'htmlall':'UTF-8'} hide">
        <label class="control-label col-lg-3">
            <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title=" ">{l s='Text color hover' mod='mpm_topmenu'}</span>
        </label>
        <div class="col-lg-9">
            <div class="form-group">
                <div class="col-lg-2">
                    <div class="row">
                        <div class="input-group">
                            <input type="color" data-hex="true" class="color mColorPickerInput"  name="text_color_group_hover_{$ident|escape:'htmlall':'UTF-8'}" value="#000000" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group form-group-before form_group_class_{$ident|escape:'htmlall':'UTF-8'} hide">
        <label class="control-label col-lg-3">
            <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title=" ">{l s='Text displayed before column' mod='mpm_topmenu'}</span>
        </label>
        {assign var=use_textarea_autosize value=true}
        <div class="col-lg-9">
            {if  count($languages) > 1}
                {foreach $languages as $language}
                    <div class="form-group form_group_class_{$ident|escape:'htmlall':'UTF-8'} translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}"{if $language.id_lang != $defaultFormLanguage} style="display:none;"{/if}>
                        <div class="col-lg-9">
                            <textarea  name="description_group_before_{$ident|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}" id="description_group_before_{$ident|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}" class="rte autoload_rte" > </textarea>
                        </div>
                        <div class="col-lg-2">
                            <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                {$language.iso_code|escape:'htmlall':'UTF-8'}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                {foreach from=$languages item=language}
                                    <li>
                                        <a href="javascript:hideOtherLanguage({$language.id_lang|escape:'htmlall':'UTF-8'});" tabindex="-1">{$language.name|escape:'htmlall':'UTF-8'}</a>
                                    </li>
                                {/foreach}
                            </ul>
                        </div>
                    </div>
                {/foreach}
            {else}
                <textarea name="description_group_before_{$ident|escape:'htmlall':'UTF-8'}_{$defaultFormLanguage|escape:'htmlall':'UTF-8'}" id="description_group_before_{$ident|escape:'htmlall':'UTF-8'}_{$defaultFormLanguage|escape:'htmlall':'UTF-8'}"  class="rte autoload_rte" > </textarea>
            {/if}

        </div>
    </div>

    <div class="form-group form-group-after form_group_class_{$ident|escape:'htmlall':'UTF-8'} hide">
        <label class="control-label col-lg-3">
            <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title=" ">{l s='Text displayed after column' mod='mpm_topmenu'}</span>
        </label>
        {assign var=use_textarea_autosize value=true}
        <div class="col-lg-9">
            {if  count($languages) > 1}
                {foreach $languages as $language}
                    <div class="form-group form_group_class_{$ident|escape:'htmlall':'UTF-8'} translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}"{if $language.id_lang != $defaultFormLanguage} style="display:none;"{/if}>
                        <div class="col-lg-9">
                            <textarea  name="description_group_after_{$ident|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}" id="description_group_after_{$ident|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}" class="rte autoload_rte" > </textarea>
                        </div>
                        <div class="col-lg-2">
                            <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                {$language.iso_code|escape:'htmlall':'UTF-8'}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                {foreach from=$languages item=language}
                                    <li>
                                        <a href="javascript:hideOtherLanguage({$language.id_lang|escape:'htmlall':'UTF-8'});" tabindex="-1">{$language.name|escape:'htmlall':'UTF-8'}</a>
                                    </li>
                                {/foreach}
                            </ul>
                        </div>
                    </div>
                {/foreach}
            {else}
                <textarea name="description_group_after_{$ident|escape:'htmlall':'UTF-8'}_{$defaultFormLanguage|escape:'htmlall':'UTF-8'}" id="description_group_after_{$ident|escape:'htmlall':'UTF-8'}_{$defaultFormLanguage|escape:'htmlall':'UTF-8'}"  class="rte autoload_rte" > </textarea>
            {/if}
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            $( ".getFormGroup .tab_column_content" ).sortable({
                forcePlaceholderSize: true,
                axis: 'y',
                update: function() { updatePositionGroup({$id_topmenu_column|escape:'htmlall':'UTF-8'}) },
            });
            $('.block_item_group_{$ident|escape:'htmlall':'UTF-8'} .mColorPickerInput').mColorPicker();
            tinySetup({
                editor_selector :"autoload_rte"
            });
        });
    </script>



</div>