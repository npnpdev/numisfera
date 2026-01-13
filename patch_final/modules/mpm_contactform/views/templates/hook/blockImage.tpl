{if $settings['block_image']}
    <div class="block_image_front" style="width:{$settings['width_image']|escape:'htmlall':'UTF-8'}%">
        <div class="content_form">
            {if $settings['title_block_image']}
                <div class="title_column_form">{$settings['title_block_image']|escape:'htmlall':'UTF-8'}</div>
            {/if}
            <div class="cont_column_form">
                {if $images}
                    <img src="{$images|escape:'htmlall':'UTF-8'}" alt="{$settings['title_block_image']|escape:'htmlall':'UTF-8'}">
                {/if}
            </div>
        </div>
    </div>
{/if}