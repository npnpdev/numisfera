<div data-id="{$id|escape:'htmlall':'UTF-8'}" class="block_item_column block_item_column_{$id|escape:'htmlall':'UTF-8'}">

    <div class="button_block button_block_{$id|escape:'htmlall':'UTF-8'}">
        <a data-id="{$id|escape:'htmlall':'UTF-8'}" class="position_column"><i class="material-icons">open_with</i></a>
        <label class="button_block_label">Column_{$id|escape:'htmlall':'UTF-8'}</label>

        <div class="button_item">
            <button data-id="{$id|escape:'htmlall':'UTF-8'}" type="button" class="btn btn-default add_column"><i class="material-icons">add_circle_outline</i><span>{l s='Add new column' mod='mpm_topmenu'}</span></button>
            <button data-id="{$id|escape:'htmlall':'UTF-8'}" type="button" class="btn btn-default save_column"><i class="material-icons">save</i><span>{l s='Save column' mod='mpm_topmenu'}</span></button>
            <button data-id="{$id|escape:'htmlall':'UTF-8'}" type="button" class="btn btn-default open_column"><i class="material-icons">mode_edit</i><span>{l s='Edit column' mod='mpm_topmenu'}</span></button>
            <button data-id="{$id|escape:'htmlall':'UTF-8'}" type="button" class="btn btn-default remove_column"><i class="material-icons">delete</i><span>{l s='Remove column' mod='mpm_topmenu'}</span></button>
            <input type="hidden" name="id_topmenu_column_{$id|escape:'htmlall':'UTF-8'}" class="id_topmenu_column">

        </div>
    </div>

    <div class="form-group form_group_class_top_line form_group_class_{$id|escape:'htmlall':'UTF-8'} hide">
        <label class="control-label col-lg-3">{l s='Active' mod='mpm_topmenu'}</label>
        <div class="col-lg-9">
            <span class="switch prestashop-switch fixed-width-lg">
                <input type="radio" name="active_column_{$id|escape:'htmlall':'UTF-8'}" id="active_column_{$id|escape:'htmlall':'UTF-8'}_on" value="1">
                <label for="active_column_{$id|escape:'htmlall':'UTF-8'}_on">{l s='Yes' mod='mpm_topmenu'}</label>
                <input type="radio" name="active_column_{$id|escape:'htmlall':'UTF-8'}" id="active_column_{$id|escape:'htmlall':'UTF-8'}_off" value="0" checked="checked">
                <label for="active_column_{$id|escape:'htmlall':'UTF-8'}_off">{l s='No' mod='mpm_topmenu'}</label>
                <a class="slide-button btn"></a>
			</span>
        </div>
    </div>

    <div class="form-group block_settings_number form_group_class_{$id|escape:'htmlall':'UTF-8'} hide">
        <label class="control-label col-lg-3">{l s=' Title column ' mod='mpm_topmenu'} </label>
        <div class="col-lg-9">
            <input type="text" name="title_column_{$id|escape:'htmlall':'UTF-8'}" class="title_column_{$id|escape:'htmlall':'UTF-8'}" value="Column_{$id|escape:'htmlall':'UTF-8'}">
            <p class="help-block">{l s='(is not displayed in front office)' mod='mpm_topmenu'} </p>
        </div>
    </div>
    <div class="form-group block_settings_number form_group_class_{$id|escape:'htmlall':'UTF-8'} hide">
        <label class="control-label col-lg-3">{l s=' Width (px)' mod='mpm_topmenu'} </label>
        <div class="col-lg-9">
            <input type="text" name="width_{$id|escape:'htmlall':'UTF-8'}" id="width" value="0" class="">
            <p class="help-block">{l s=' (Put 0 for automatic width)' mod='mpm_topmenu'} </p>
        </div>
    </div>


    <div class="form-group form_group_class_{$id|escape:'htmlall':'UTF-8'} hide">
        <label class="control-label col-lg-3">
            <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title=" ">{l s='Background color' mod='mpm_topmenu'}</span>
        </label>
        <div class="col-lg-9">
            <div class="form-group">
                <div class="col-lg-2">
                    <div class="row">
                        <div class="input-group">
                            <input type="color" data-hex="true" class="color mColorPickerInput"  name="background_color_column_{$id|escape:'htmlall':'UTF-8'}" value="#ffffff" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="form-group form_group_class_{$id|escape:'htmlall':'UTF-8'} hide">
        <label class="control-label col-lg-3">
            <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title=" ">{l s='Text color' mod='mpm_topmenu'}</span>
        </label>
        <div class="col-lg-9">
            <div class="form-group">
                <div class="col-lg-2">
                    <div class="row">
                        <div class="input-group">
                            <input type="color" data-hex="true" class="color mColorPickerInput"  name="text_color_column_{$id|escape:'htmlall':'UTF-8'}" value="#000000" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="form-group form_group_class_{$id|escape:'htmlall':'UTF-8'} hide">
        <label class="control-label col-lg-3">
            <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title=" ">{l s='Text color hover' mod='mpm_topmenu'}</span>
        </label>
        <div class="col-lg-9">
            <div class="form-group">
                <div class="col-lg-2">
                    <div class="row">
                        <div class="input-group">
                            <input type="color" data-hex="true" class="color mColorPickerInput"  name="text_color_column_hover_{$id|escape:'htmlall':'UTF-8'}" value="#000000" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group form-group-before form_group_class_{$id|escape:'htmlall':'UTF-8'} hide">
        <label class="control-label col-lg-3">
            <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title=" ">{l s='Text displayed before column' mod='mpm_topmenu'}</span>
        </label>
        {assign var=use_textarea_autosize value=true}
        <div class="col-lg-9">
            {if  count($languages) > 1}
                {foreach $languages as $language}
                    <div class="form-group form_group_class_{$id|escape:'htmlall':'UTF-8'} translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}"{if $language.id_lang != $defaultFormLanguage} style="display:none;"{/if}>
                        <div class="col-lg-9">
                            <textarea  name="description_column_before_{$id|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}" id="description_column_before_{$id|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}" class="rte autoload_rte" > </textarea>
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
                <textarea name="description_column_before_{$id|escape:'htmlall':'UTF-8'}_{$defaultFormLanguage|escape:'htmlall':'UTF-8'}" id="description_column_before_{$id|escape:'htmlall':'UTF-8'}_{$defaultFormLanguage|escape:'htmlall':'UTF-8'}"  class="rte autoload_rte" > </textarea>
            {/if}

        </div>
    </div>

    <div class="form-group form-group-after form_group_class_{$id|escape:'htmlall':'UTF-8'} hide">
        <label class="control-label col-lg-3">
            <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title=" ">{l s='Text displayed after column' mod='mpm_topmenu'}</span>
        </label>
        {assign var=use_textarea_autosize value=true}
        <div class="col-lg-9">
            {if  count($languages) > 1}
                {foreach $languages as $language}
                    <div class="form-group form_group_class_{$id|escape:'htmlall':'UTF-8'} translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}"{if $language.id_lang != $defaultFormLanguage} style="display:none;"{/if}>
                        <div class="col-lg-9">
                            <textarea  name="description_column_after_{$id|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}" id="description_column_after_{$id|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}" class="rte autoload_rte" > </textarea>
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
                <textarea name="description_column_after_{$id|escape:'htmlall':'UTF-8'}_{$defaultFormLanguage|escape:'htmlall':'UTF-8'}" id="description_column_after_{$id|escape:'htmlall':'UTF-8'}_{$defaultFormLanguage|escape:'htmlall':'UTF-8'}"  class="rte autoload_rte" > </textarea>
            {/if}
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            tinySetup({
                editor_selector :"autoload_rte"
            });
        });
    </script>



</div>