{if isset($settings) && $settings}
    {foreach $settings as $value}
        <div class="block_featured_slider">
            <div class="header_featured_slider"><span>{$value['title']|escape:'htmlall':'UTF-8'}</span></div>
            <div class="content_featured_slider grid" id="products">
                <div class="featured-list products">
                    {foreach $value['products'] as $product}
                        {include file='catalog/_partials/miniatures/product.tpl' product=$product}
                    {/foreach}
                </div>
            </div>
        </div>
    {/foreach}
{/if}