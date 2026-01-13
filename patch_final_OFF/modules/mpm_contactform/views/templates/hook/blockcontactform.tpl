{extends file='page.tpl'}
{block name='left_column'} {/block}

{block name='page_content'}

    <div class="contactFormContent" id="contactFormContent">

        {if $settings['block_description'] && $settings['position_description'] == 'left'}
            {include file="module:mpm_contactform/views/templates/hook/blockDescription.tpl"  node=1}
        {/if}

        {if $settings['block_form'] && $settings['position_form'] == 'left'}
            {include file="module:mpm_contactform/views/templates/hook/blockForm.tpl"}
        {/if}

        {if $settings['block_image'] && $settings['position_image'] == 'left'}
            {include file="module:mpm_contactform/views/templates/hook/blockImage.tpl" node=1}
        {/if}

        {if $settings['block_maps'] && $settings['position_maps'] == 'left'}
            {include file="module:mpm_contactform/views/templates/hook/blockMaps.tpl" node=1}
        {/if}


        {if $settings['block_description'] && $settings['position_description'] == 'center'}
            {include file="module:mpm_contactform/views/templates/hook/blockDescription.tpl" node=2}
        {/if}

        {if $settings['block_form'] && $settings['position_form'] == 'center'}
            {include file="module:mpm_contactform/views/templates/hook/blockForm.tpl" node=2}
        {/if}

        {if $settings['block_image'] && $settings['position_image'] == 'center'}
            {include file="module:mpm_contactform/views/templates/hook/blockImage.tpl" node=2}
        {/if}

        {if $settings['block_maps'] && $settings['position_maps'] == 'center'}
            {include file="module:mpm_contactform/views/templates/hook/blockMaps.tpl" node=2}
        {/if}

        {if $settings['block_description'] && $settings['position_description'] == 'right'}
            {include file="module:mpm_contactform/views/templates/hook/blockDescription.tpl" node=3}
        {/if}

        {if $settings['block_form'] && $settings['position_form'] == 'right'}
            {include file="module:mpm_contactform/views/templates/hook/blockForm.tpl" node=3}
        {/if}

        {if $settings['block_image'] && $settings['position_image'] == 'right'}
            {include file="module:mpm_contactform/views/templates/hook/blockImage.tpl" node=3}
        {/if}

        {if $settings['block_maps'] && $settings['position_maps'] == 'right'}
            {include file="module:mpm_contactform/views/templates/hook/blockMaps.tpl" node=3}
        {/if}

        {if $settings['block_description'] && $settings['position_description'] == 'bottom'}
            {include file="module:mpm_contactform/views/templates/hook/blockDescription.tpl" node=4}
        {/if}

        {if $settings['block_form'] && $settings['position_form'] == 'bottom'}
            {include file="module:mpm_contactform/views/templates/hook/blockForm.tpl" node=4}
        {/if}

        {if $settings['block_image'] && $settings['position_image'] == 'bottom'}
            {include file="module:mpm_contactform/views/templates/hook/blockImage.tpl" node=4}
        {/if}

        {if $settings['block_maps'] && $settings['position_maps'] == 'bottom'}
            {include file="module:mpm_contactform/views/templates/hook/blockMaps.tpl" node=4}
        {/if}

        <div style="clear: both"></div>
        <div class="hidden_block">
            <input type="hidden" class="id_shop" name="idShop" value="{$id_shop|escape:'htmlall':'UTF-8'}">
            <input type="hidden" class="id_lang" name="idLang" value="{$id_lang|escape:'htmlall':'UTF-8'}">
            {*<input type="hidden" value="{$base_dir|escape:'htmlall':'UTF-8'}" name="basePath">*}
        </div>
        <div class="form_notice_contact_form_ov"> </div>
        <div class="form_notice_contact_form_hidden">
            <div class="notice_error">{l s='Some error occurred please contact us!' mod='mpm_contactform'} </div>
            <div class="notice_success">{l s='Message successfully sent! Our manager will contact you!' mod='mpm_contactform'} </div>
        </div>
    </div>

    <style>
        .contactFormContent, .form_notice_contact_form{
            {if $settings['background']}
                background-color: {$settings['background']|escape:'htmlall':'UTF-8'};
                border: 1px solid {$settings['background']|escape:'htmlall':'UTF-8'};
            {else}
                background-color: #FAFAFA;
                border: 1px solid #FAFAFA;
            {/if}



            {if $settings['color']}
                color: {$settings['color']|escape:'htmlall':'UTF-8'};
            {else}
                color: #000000;
            {/if}
        }





    </style>

{/block}