<div class="product-block-info">
    <ul class="block-del">
        {foreach from=$settings key=key item=value}
            <li class="product-block-item {$class|escape:'htmlall':'UTF-8'}  {if $value['image']} isset_images {/if}" >

                <div class="content-item">
                    {if $value['image']}
                        <div class="info-image">
                             <img class="block_img" src="{$value['image']|escape:'htmlall':'UTF-8'}" alt="{$value['title']|escape:'htmlall':'UTF-8'}">
                        </div>
                    {/if}

                    <div class="info-content">
                        <span class="block_title">{$value['title']|escape:'htmlall':'UTF-8'}</span>
                        <div class="block_description">{$value['description']|escape:'htmlall':'UTF-8' nofilter}</div>
                    </div>
                    <div style="clear: both"></div>
                </div>

            </li>
        {/foreach}
    </ul>
</div>