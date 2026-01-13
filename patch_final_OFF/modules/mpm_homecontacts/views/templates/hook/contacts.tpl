{if isset($items) && $items}
    <div class="homecontacts">
        <div class="homecontacts_phone item_block_contact">
            <div class="item_content">
                <div class="phone_icon item_block_icon"><i class="material-icons">speaker_phone</i></div>
                <div class="homecontacts_phone_right">
                    <div class="phone_block item_block_title">{$items['phone']|escape:'htmlall':'UTF-8'}</div>
                    <div class="phone_description_block item_block_description">{if $items['phone_description']}{$items['phone_description']|escape:'htmlall':'UTF-8' nofilter}{/if}</div>
                </div>
            </div>
        </div>
        <div class="homecontacts_email item_block_contact">
            <div class="item_content">
                <div class="email_icon item_block_icon"><i class="material-icons">email</i></div>
                <div class="homecontacts_email_right">
                    <div class="email_block item_block_title">{$items['email']|escape:'htmlall':'UTF-8'}</div>
                    <div class="email_description_block item_block_description">{if $items['email_description']}{$items['email_description']|escape:'htmlall':'UTF-8' nofilter}{/if}</div>
                </div>
            </div>
        </div>
        <div class="homecontacts_working_days item_block_contact">
            <div class="item_content">
                <div class="working_days_icon item_block_icon" ><i class="material-icons">access_time</i></div>
                <div class="homecontacts_working_days_right">
                    <div class="working_days_block item_block_title">{$items['working_days']|escape:'htmlall':'UTF-8'}</div>
                    <div class="working_days_description_block item_block_description">{if $items['working_days_description']}{$items['working_days_description']|escape:'htmlall':'UTF-8' nofilter}{/if}</div>
                </div>
            </div>
        </div>
        <div style="clear: both"></div>
    </div>
{/if}