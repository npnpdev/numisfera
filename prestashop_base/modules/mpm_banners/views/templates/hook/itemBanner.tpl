{if isset($settings) && $settings}
    {foreach $settings as $value}
        <div data-id="{$value['id_banners']|escape:'htmlall':'UTF-8'}" class="block_banners block_banner_{$value['id_banners']|escape:'htmlall':'UTF-8'}" >
            <div class="content_banners">
				{if $value['width_description_left'] || $value['image_left']}
					<div class="banners_left_column" style="width: {$value['width_block_left']|escape:'htmlall':'UTF-8'}%; background: url('{$value['image_left']|escape:'htmlall':'UTF-8'}')  0% 0% / auto 100% no-repeat;" >
						{if isset($value['description_left']) && $value['description_left']}
							<div class="description_left" style="width: {$value['width_description_left']|escape:'htmlall':'UTF-8'}px; {if $value['position_description_left'] == 'center'} margin: 0 auto; {else}float: {$value['position_description_left']|escape:'htmlall':'UTF-8'}{/if}">{$value['description_left']|escape:'htmlall':'UTF-8' nofilter}</div>
						{/if}
					</div>
				 {/if}
				{if $value['width_description_right'] || $value['image_right']}
					<div class="banners_right_column" style="width: {$value['width_block_right']|escape:'htmlall':'UTF-8'}%; background: url('{$value['image_right']|escape:'htmlall':'UTF-8'}')  0% 0% / auto 100% no-repeat;">
						{if isset($value['description_right']) && $value['description_right']}
							<div class="description_left" style="width: {$value['width_description_right']|escape:'htmlall':'UTF-8'}px; {if $value['position_description_right'] == 'center'} margin: 0 auto; {else}float: {$value['position_description_right']|escape:'htmlall':'UTF-8'}{/if}">{$value['description_right']|escape:'htmlall':'UTF-8' nofilter}</div>
						{/if}
					</div>
				 {/if}
            </div>

            <style>
                .block_banner_{$value['id_banners']|escape:'htmlall':'UTF-8'},
                .block_banner_{$value['id_banners']|escape:'htmlall':'UTF-8'} .content_banners,
                .block_banner_{$value['id_banners']|escape:'htmlall':'UTF-8'} .banners_left_column,
                .block_banner_{$value['id_banners']|escape:'htmlall':'UTF-8'} .banners_right_column{
                    min-height: {$value['min_height']|escape:'htmlall':'UTF-8'}px !important;
                }
                .block_banner_{$value['id_banners']|escape:'htmlall':'UTF-8'} .banners_left_column{
                    background-color: {$value['background_color_left']|escape:'htmlall':'UTF-8'} !important;
                }
                .block_banner_{$value['id_banners']|escape:'htmlall':'UTF-8'}  .banners_right_column{
                    background-color: {$value['background_color_right']|escape:'htmlall':'UTF-8'} !important;
                }
            </style>

        </div>
    {/foreach}
{/if}