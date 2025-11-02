{if $settings['block_maps']}
    <div class="block_maps_front" style="width:{$settings['width_maps_block']|escape:'htmlall':'UTF-8'}%">
        <div class="content_form"  id="block_maps_gm" style="width: {$settings['width_maps']|escape:'htmlall':'UTF-8'}px" >
            {if $settings['title_block_maps']}
                <div class="title_column_form">{$settings['title_block_maps']|escape:'htmlall':'UTF-8'}</div>
            {/if}
            <iframe src="{if $maps}{$maps|escape:'htmlall':'UTF-8'}{/if}" width="{$settings['width_maps']|escape:'htmlall':'UTF-8'}" height="{$settings['height_maps']|escape:'htmlall':'UTF-8'}" frameborder="0" style="border:0" ></iframe>
        </div>
    </div>
{/if}