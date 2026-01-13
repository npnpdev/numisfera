<div class="productsBlock">
    <div class="products_list">
        {foreach from=$products item=product}

            <article class="product-miniature js-product-miniature" data-id-product="{$product.id_product|escape:'htmlall':'UTF-8'}" data-id-product-attribute="{$product.id_product_attribute|escape:'htmlall':'UTF-8'}">
                <div class="thumbnail-container">
                    {if isset($img) && $img}
                        {block name='product_thumbnail'}
                            <a href="{$product.url|escape:'htmlall':'UTF-8'}" class="thumbnail product-thumbnail">
                                <img src = "{$product.cover.bySize.{$type_img}.url|escape:'htmlall':'UTF-8'}" alt = "{$product.cover.legend|escape:'htmlall':'UTF-8'}" data-full-size-image-url = "{$product.cover.large.url|escape:'htmlall':'UTF-8'}" >
                            </a>
                        {/block}
                    {/if}
                    <div class="product-description">

                        {if isset($title) && $title}
                            {block name='product_name'}
                                <h2 class="h3 product-title"><a href="{$product.url|escape:'htmlall':'UTF-8'}">{$product.name|escape:'htmlall':'UTF-8'|truncate:30:'...'}</a></h2>
                            {/block}
                        {/if}

                        {if isset($price) && $price}
                            {block name='product_price_and_shipping'}
                                {if $product.show_price}
                                    <div class="product-price-and-shipping">

                                        <span  class="price">{$product.price|escape:'htmlall':'UTF-8'}</span>

                                        {if $product.has_discount}
                                            {hook h='displayProductPriceBlock' product=$product type="old_price"}
                                            <span class="regular-price">{$product.regular_price|escape:'htmlall':'UTF-8'}</span>
                                        {/if}

                                        {hook h='displayProductPriceBlock' product=$product type="before_price"}
                                        {hook h='displayProductPriceBlock' product=$product type='unit_price'}
                                        {hook h='displayProductPriceBlock' product=$product type='weight'}
                                    </div>
                                {/if}
                            {/block}
                        {/if}
                    </div>
                    {if isset($button) && $button}
                        <div class="product-add-to-cart">
                            <form action="{Context::getContext()->link->getPageLink('cart',true)|escape:'htmlall':'UTF-8'}" method="post" class="add-to-cart-or-refresh">
                                <input type="hidden" name="token" value="{Tools::getToken(false)|escape:'htmlall':'UTF-8'}">
                                <input type="hidden" name="id_product" value="{$product.id_product|escape:'htmlall':'UTF-8'}" class="product_page_product_id">
                                <input type="hidden" name="id_customization" value="0" class="product_customization_id">
                                <button class="btn add-to-cart add_cart_brandfashion" {if  $product.available_for_order && $product.minimal_quantity>$product.quantity}disabled{/if} data-button-action="add-to-cart" type="submit" >
                                    <span class="add_to_cart_icon"> <i class="material-icons shopping-cart">shopping_cart</i> </span>
                                    <span class="add_to_cart_tittle"> {l s='Add to cart' mod='mpm_topmenu'} </span>
                                </button>
                            </form>
                        </div>
                    {/if}
                </div>
            </article>
        {/foreach}
    </div>
</div>