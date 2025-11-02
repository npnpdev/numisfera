
<div class="block-category-info">

    {if isset($image) && $image && $cat['img']['bySize']['category_default']}
        <div class="block-category-cover">
            <img alt="{$cat['name']|escape:'htmlall':'UTF-8'}" src="{$cat['img']['bySize']['category_default']['url']|escape:'htmlall':'UTF-8'}">
        </div>
    {/if}

    <div class="block-category-description
                {if isset($image) && $image && $cat['img']['bySize']['category_default']['url']}isset_image_cat{else}no_isset_image_cat{/if}
                {if (isset($image) && $image && $cat['img']['bySize']['category_default']['url'])}{else}no_image_description_cat{/if}
                 ">
        <h1 class="h1">{$cat['name']|escape:'htmlall':'UTF-8'}</h1>
        {if $cat['description'] && isset($description) && $description}
            <div id="block-category-description">{$cat['description']|escape:'htmlall':'UTF-8' nofilter}</div>
        {/if}
    </div>

    <div style="clear: both"></div>
</div>


{if isset($subcategories) && $subcategories && $active}

    <div class="block_subcategories">
        <div class="title_subcategories_block">
            <div class="title">
                {l s='Categories' mod='mpm_subcategories'}
            </div>
        </div>
        <ul data-count="{count($subcategories)|escape:'htmlall':'UTF-8'}" id="categories_slider" class="categories_slider">
            {foreach from=$subcategories item="category"}

                <li class="subcategories_item">
                    <a class="subcategory-image" href="{$category['link']|escape:'htmlall':'UTF-8'}">
                        {if isset($category['images']['bySize']['subcategories_default']) && $category['images']['bySize']['subcategories_default']}
                            <img src="{$category['images']['bySize']['subcategories_default']['url']|escape:'htmlall':'UTF-8'}" alt="{$category['name']|escape:'htmlall':'UTF-8'}">
                        {/if}
                    </a>
                    <a class="subcategory-name" href="{$category['link']|escape:'htmlall':'UTF-8'}">
                        <span>{$category['name']|escape:'htmlall':'UTF-8'}</span>
                    </a>
                </li>

            {/foreach}
        </ul>
    </div>

{/if}