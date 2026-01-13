{if $settings['block_description']}
    <div class="block_description_front" style="width:{$settings['width_description']|escape:'htmlall':'UTF-8'}%">
        <div class="content_form">
            {if $settings['title_block_description']}
                <div class="title_column_form">{$settings['title_block_description']|escape:'htmlall':'UTF-8' nofilter}</div>
            {/if}
            <div class="cont_column_form">{$settings['description']|escape:'htmlall':'UTF-8' nofilter}</div>
        </div>
    </div>
{/if}