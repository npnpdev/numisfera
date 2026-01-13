
{function name="categories" nodes=[] depth=0}
    {strip}
        {if $nodes|count}
            <ul>
                {foreach from=$nodes item=node}
                    <li>
                        <a href="{$node.link|escape:'htmlall':'UTF-8'}">{$node.name|escape:'htmlall':'UTF-8'}</a>
                    </li>
                    <li>
                        {categories nodes=$node.children depth=$depth+1}
                    </li>
                {/foreach}
            </ul>
        {/if}
    {/strip}
{/function}

<div class="category-tree-top">
    <ul>
        {foreach $categories as $category}
            <li><a href="{$category.link|escape:'htmlall':'UTF-8' nofilter}">{$category.name|escape:'htmlall':'UTF-8'}</a></li>
            {if $subcategories}
                <li>{categories nodes=$category.children}</li>
            {/if}
        {/foreach}
    </ul>
</div>