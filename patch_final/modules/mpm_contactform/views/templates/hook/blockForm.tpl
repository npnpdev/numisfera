{if $settings['block_form']}
    <div class="block_contact_form_front" id="block_contact_form_front" style="width:{$settings['width_form']|escape:'htmlall':'UTF-8'}%">
        <div class="content_form">
            {if $settings['title_block_form']}
                <div class="title_column_form">{$settings['title_block_form']|escape:'htmlall':'UTF-8'}</div>
            {/if}
            <div class="cont_column_form">
                {if $settings['name_field']}
                    <div class="one_field_form one_field_line">
                        <span class="label_field_name">{l s='Name' mod='mpm_contactform'}{if $settings['name_field_required']}<sup>*</sup>{/if}</span>
                        <input type="text" class="user_name">
                    </div>
                {/if}
                {if $settings['email_field']}
                    <div class="one_field_form one_field_line">
                        <span class="label_field_name">{l s='Email address' mod='mpm_contactform'}{if $settings['email_field_required']}<sup>*</sup>{/if}</span>
                        <input type="text" class="user_email">
                    </div>
                {/if}
                {if $settings['phone_field']}
                    <div class="one_field_form one_field_line">
                        <span class="label_field_name">{l s='Phone Number' mod='mpm_contactform'}{if $settings['phone_field_required']}<sup>*</sup>{/if}</span>
                        <input type="text" class="user_phone">
                    </div>
                {/if}
                {if $settings['subject_field']}
                    <div class="one_field_form one_field_line">
                        <span class="label_field_name">{l s='Subject Heading' mod='mpm_contactform'}{if $settings['subject_field_required']}<sup>*</sup>{/if}</span>
                        <input type="text" class="subject_message">
                    </div>
                {/if}
                {if $settings['attach_field']}
                    <div  class="one_field_form one_field_form_attach">
                        <span class="label_field_name">{l s='Attach File' mod='mpm_contactform'}</span>
                        <input type="file" name="fileUpload" class="filestyle"/>
                    </div>
                {/if}
                <div class="one_field_form one_field_line">
                    <span class="label_field_name">{l s='Message' mod='mpm_contactform'}<sup>*</sup></span>
                    <textarea class="message"></textarea>
                </div>
                {if $settings['captcha_field']}
                    <div class="one_field_form one_field_form_captcha one_field_line">
                        <div class='captch_img_block_contact'><img src="{$captcha_url|escape:'htmlall':'UTF-8'}"></div>
                        <div class='block_result_captcha'>
                            <input type='text' class='result_captcha' name='result_captcha' >
                        </div>
                        <div style="clear: both"></div>
                    </div>
                {/if}
                <div class="one_field_form one_field_form_button" id="one_field_form_button">
                    <button class="send_contact_form_message btn btn-primary" onclick="">
                        <span>{l s='Send message' mod='mpm_contactform'}</span>
                    </button>
                    <input type='hidden' class="base_url" name='base_url' value="{$base_url|escape:'htmlall':'UTF-8'}">
                </div>
            </div>
        </div>
    </div>
{/if}