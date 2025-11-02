<ul class="related_articles_product_page">
    {foreach from=$articles item=value}
        <li class="related_articles_item">
            <a class="title_related_articles" href="{$blogUrl|escape:'htmlall':'UTF-8'}{$value['link_rewrite_category']|escape:'htmlall':'UTF-8'}/{$value['link_rewrite']|escape:'htmlall':'UTF-8'}.html">
                <i class="material-icons">keyboard_arrow_right</i> {$value['name']|truncate:90|escape:'htmlall':'UTF-8'}
            </a>
        </li>
    {/foreach}
</ul>