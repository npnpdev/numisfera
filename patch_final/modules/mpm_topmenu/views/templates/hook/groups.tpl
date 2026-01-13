
{foreach $groups as $key => $group}



    <div data-id="{$group['ident']|escape:'htmlall':'UTF-8'}" class="block_item_group block_item_group_{$group['ident']|escape:'htmlall':'UTF-8'}">

        <div class="button_block_group button_block_group_{$group['ident']|escape:'htmlall':'UTF-8'}">
            <a data-id="{$group['ident']|escape:'htmlall':'UTF-8'}" class="position_column"><i class="material-icons">open_with</i></a>
            <label class="button_block_label">{$group['title']|escape:'htmlall':'UTF-8'}</label>

            <div class="button_item">
                <button data-id="{$group['ident']|escape:'htmlall':'UTF-8'}" type="button" class="btn btn-default add_column"><i class="material-icons">add_circle_outline</i><span>{l s='Add new group' mod='mpm_topmenu'}</span></button>
                <button data-id="{$group['ident']|escape:'htmlall':'UTF-8'}" type="button" class="btn btn-default save_column"><i class="material-icons">save</i><span>{l s='Save group' mod='mpm_topmenu'}</span></button>
                <button data-id="{$group['ident']|escape:'htmlall':'UTF-8'}" type="button" class="btn btn-default open_column"><i class="material-icons">mode_edit</i><span>{l s='Edit group' mod='mpm_topmenu'}</span></button>
                <button data-id="{$group['ident']|escape:'htmlall':'UTF-8'}" type="button" class="btn btn-default remove_column"><i class="material-icons">delete</i><span>{l s='Remove group' mod='mpm_topmenu'}</span></button>
                <input type="hidden" name="current_column" class="current_column" value="{$id_topmenu_column|escape:'htmlall':'UTF-8'}">
                <input type="hidden" name="id_group" class="id_group" value="{$group['id_topmenu_group']|escape:'htmlall':'UTF-8'}">

            </div>
        </div>

        <div class="form-group form_group_class_top_line form_group_class_{$group['ident']|escape:'htmlall':'UTF-8'} hide">
            <label class="control-label col-lg-3">{l s='Active' mod='mpm_topmenu'}</label>
            <div class="col-lg-9">
            <span class="switch prestashop-switch fixed-width-lg">
                <input type="radio" name="active_group_{$group['ident']|escape:'htmlall':'UTF-8'}" id="active_group_{$group['ident']|escape:'htmlall':'UTF-8'}_on" value="1" {if isset($group['active']) && $group['active']}checked="checked"{/if}>
                <label for="active_group_{$group['ident']|escape:'htmlall':'UTF-8'}_on">{l s='Yes' mod='mpm_topmenu'}</label>
                <input type="radio" name="active_group_{$group['ident']|escape:'htmlall':'UTF-8'}" id="active_group_{$group['ident']|escape:'htmlall':'UTF-8'}_off" value="0" {if !isset($group['active']) || !$group['active']}checked="checked"{/if}>
                <label for="active_group_{$group['ident']|escape:'htmlall':'UTF-8'}_off">{l s='No' mod='mpm_topmenu'}</label>
                <a class="slide-button btn"></a>
			</span>
            </div>
        </div>

        <div class="form-group block_settings_number form_group_class_{$group['ident']|escape:'htmlall':'UTF-8'} hide">
            <label class="control-label col-lg-3">{l s=' Title group ' mod='mpm_topmenu'} </label>
            <div class="col-lg-9">
                <input type="text" name="title_group_{$group['ident']|escape:'htmlall':'UTF-8'}" class="title_group_{$group['ident']|escape:'htmlall':'UTF-8'}" value="{$group['title']|escape:'htmlall':'UTF-8'}">
                <p class="help-block">{l s='(is not displayed in front office)' mod='mpm_topmenu'} </p>
            </div>
        </div>

        <div class="form-group form-group-title form_group_class_{$group['ident']|escape:'htmlall':'UTF-8'} hide">
            <label class="control-label col-lg-3">{l s=' Title ' mod='mpm_topmenu'} </label>
            <div class="col-lg-9">
                {if count($languages) > 1}
                    <div class="form-group">
                        {foreach $languages as $language}
                            {if count($languages) > 1}
                                <div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                                <div class="col-lg-9">
                            {/if}

                            <input type="text" id="titleitem_{$language.id_lang|escape:'htmlall':'UTF-8'}"  name="titleitem_{$language.id_lang|escape:'htmlall':'UTF-8'}" value="{if isset($group['title_front'][$language.id_lang]) && $group['title_front'][$language.id_lang]}{$group['title_front'][$language.id_lang]|escape:'htmlall':'UTF-8'}{/if}"  />

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
                    <input type="text" id="titleitem_{$defaultFormLanguage|escape:'htmlall':'UTF-8'}"  name="titleitem_{$defaultFormLanguage|escape:'htmlall':'UTF-8'}"  value="{if isset($group['title_front'][$defaultFormLanguage]) && $group['title_front'][$defaultFormLanguage]}{$group['title_front'][$defaultFormLanguage]|escape:'htmlall':'UTF-8'}{/if}" />
                {/if}
                <p class="help-block">{l s='(is displayed in front office)' mod='mpm_topmenu'} </p>
            </div>
        </div>



        <div class="form-group block_settings_number form_group_class_{$group['ident']|escape:'htmlall':'UTF-8'} hide">
            <label class="control-label col-lg-3">{l s='Type' mod='mpm_topmenu'} </label>
            <div class="col-lg-9">
                <select data-id="{$group['ident']|escape:'htmlall':'UTF-8'}" class="type" name="type_{$group['ident']|escape:'htmlall':'UTF-8'}" >
                    <option {if isset($group['type']) && $group['type'] == 'product'}selected="selected"{/if} value="product">{l s='Products' mod='mpm_topmenu'}</option>
                    <option  {if isset($group['type']) && $group['type'] == 'category'}selected="selected"{/if} value="category">{l s='Categories' mod='mpm_topmenu'}</option>
                    <option {if isset($group['type']) && $group['type'] == 'cms'}selected="selected"{/if}  value="cms">{l s='Cms' mod='mpm_topmenu'}</option>
                    <option {if isset($group['type']) && $group['type'] == 'link'}selected="selected"{/if}  value="link">{l s='Link' mod='mpm_topmenu'}</option>
                    <option  {if isset($group['type']) && $group['type'] == 'brand'}selected="selected"{/if} value="brand">{l s='Brands' mod='mpm_topmenu'}</option>
                    <option  {if isset($group['type']) && $group['type'] == 'supplier'}selected="selected"{/if} value="supplier">{l s='Suppliers' mod='mpm_topmenu'}</option>
                    <option  {if isset($group['type']) && $group['type'] == 'page'}selected="selected"{/if} value="page">{l s='Pages' mod='mpm_topmenu'}</option>
                    <option  {if isset($group['type']) && $group['type'] == 'image'}selected="selected"{/if} value="image">{l s='Image' mod='mpm_topmenu'}</option>
                    <option  {if isset($group['type']) && $group['type'] == 'description'}selected="selected"{/if} value="description">{l s='Description' mod='mpm_topmenu'}</option>
                </select>
            </div>
        </div>

        <div class="form-group form_group_type_content form_group_class_{$group['ident']|escape:'htmlall':'UTF-8'} hide">
            {$group['tpl']}
        </div>

        <div class="form-group form_group_class_{$group['ident']|escape:'htmlall':'UTF-8'} hide">
            <label class="control-label col-lg-3">
                <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title=" ">{l s='Background color' mod='mpm_topmenu'}</span>
            </label>
            <div class="col-lg-9">
                <div class="form-group">
                    <div class="col-lg-2">
                        <div class="row">
                            <div class="input-group">
                                <input type="color" data-hex="true" class="color mColorPickerInput"  name="background_color_group_{$group['ident']|escape:'htmlall':'UTF-8'}" value="{if isset($group['background_color']) && $group['background_color']}{$group['background_color']|escape:'htmlall':'UTF-8'}{/if}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="form-group form_group_class_{$group['ident']|escape:'htmlall':'UTF-8'} hide">
            <label class="control-label col-lg-3">
                <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title=" ">{l s='Text color' mod='mpm_topmenu'}</span>
            </label>
            <div class="col-lg-9">
                <div class="form-group">
                    <div class="col-lg-2">
                        <div class="row">
                            <div class="input-group">
                                <input type="color" data-hex="true" class="color mColorPickerInput"  name="text_color_group_{$group['ident']|escape:'htmlall':'UTF-8'}" value="{if isset($group['text_color']) && $group['text_color']}{$group['text_color']|escape:'htmlall':'UTF-8'}{/if}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="form-group form_group_class_{$group['ident']|escape:'htmlall':'UTF-8'} hide">
            <label class="control-label col-lg-3">
                <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title=" ">{l s='Text color hover' mod='mpm_topmenu'}</span>
            </label>
            <div class="col-lg-9">
                <div class="form-group">
                    <div class="col-lg-2">
                        <div class="row">
                            <div class="input-group">
                                <input type="color" data-hex="true" class="color mColorPickerInput"  name="text_color_group_hover_{$group['ident']|escape:'htmlall':'UTF-8'}" value="{if isset($group['text_color_hover']) && $group['text_color_hover']}{$group['text_color_hover']|escape:'htmlall':'UTF-8'}{/if}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group form-group-before form_group_class_{$group['ident']|escape:'htmlall':'UTF-8'} hide">
            <label class="control-label col-lg-3">
                <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title=" ">{l s='Text displayed before column' mod='mpm_topmenu'}</span>
            </label>
            {assign var=use_textarea_autosize value=true}
            <div class="col-lg-9">
                {if  count($languages) > 1}
                    {foreach $languages as $language}
                        <div class="form-group form_group_class_{$group['ident']|escape:'htmlall':'UTF-8'} translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}"{if $language.id_lang != $defaultFormLanguage} style="display:none;"{/if}>
                            <div class="col-lg-9">
                                <textarea  name="description_group_before_{$group['ident']|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}" id="description_group_before_{$group['ident']|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}" class="rte autoload_rte" >{if isset($group['description_before'][$language.id_lang]) && $group['description_before'][$language.id_lang]}{$group['description_before'][$language.id_lang]|escape:'htmlall':'UTF-8'}{/if} </textarea>
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
                    <textarea name="description_group_before_{$group['ident']|escape:'htmlall':'UTF-8'}_{$defaultFormLanguage|escape:'htmlall':'UTF-8'}" id="description_group_before_{$group['ident']|escape:'htmlall':'UTF-8'}_{$defaultFormLanguage|escape:'htmlall':'UTF-8'}"  class="rte autoload_rte" >{if isset($group['description_before'][$defaultFormLanguage]) && $group['description_before'][$defaultFormLanguage]}{$group['description_before'][$defaultFormLanguage]|escape:'htmlall':'UTF-8'}{/if} </textarea>
                {/if}

            </div>
        </div>

        <div class="form-group form-group-after form_group_class_{$group['ident']|escape:'htmlall':'UTF-8'} hide">
            <label class="control-label col-lg-3">
                <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title=" ">{l s='Text displayed after column' mod='mpm_topmenu'}</span>
            </label>
            {assign var=use_textarea_autosize value=true}
            <div class="col-lg-9">
                {if  count($languages) > 1}
                    {foreach $languages as $language}
                        <div class="form-group form_group_class_{$group['ident']|escape:'htmlall':'UTF-8'} translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}"{if $language.id_lang != $defaultFormLanguage} style="display:none;"{/if}>
                            <div class="col-lg-9">
                                <textarea  name="description_group_after_{$group['ident']|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}" id="description_group_after_{$group['ident']|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}" class="rte autoload_rte" >{if isset($group['description_after'][$language.id_lang]) && $group['description_after'][$language.id_lang]}{$group['description_after'][$language.id_lang]|escape:'htmlall':'UTF-8'}{/if} </textarea>
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
                    <textarea name="description_group_after_{$group['ident']|escape:'htmlall':'UTF-8'}_{$defaultFormLanguage|escape:'htmlall':'UTF-8'}" id="description_group_after_{$group['ident']|escape:'htmlall':'UTF-8'}_{$defaultFormLanguage|escape:'htmlall':'UTF-8'}"  class="rte autoload_rte" >{if isset($group['description_after'][$defaultFormLanguage]) && $group['description_after'][$defaultFormLanguage]}{$group['description_after'][$defaultFormLanguage]|escape:'htmlall':'UTF-8'}{/if} </textarea>
                {/if}
            </div>
        </div>
    </div>
{/foreach}

<script type="text/javascript">
    $(document).ready(function(){
        $( ".getFormGroup .tab_column_content" ).sortable({
            forcePlaceholderSize: true,
            axis: 'y',
            update: function() { updatePositionGroup({$id_topmenu_column|escape:'htmlall':'UTF-8'})},
        });
        $('.getFormGroup .mColorPickerInput').mColorPicker();
        tinySetup({
            editor_selector :"autoload_rte"
        });
    });
</script>