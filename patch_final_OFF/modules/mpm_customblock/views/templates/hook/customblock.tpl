{if isset($items) && $items}
    <div class="customblock">
        <ul class="custom-list">
            {foreach $items as $item}
                <li class="custom-list-item">
                    <div class="custom-item-img">{if $item['image']}<img alt="{$item['title']|escape:'htmlall':'UTF-8'}" src="{$item['image']|escape:'htmlall':'UTF-8'}">{/if}</div>
                    <div class="custom-item-title">{$item['title']|escape:'htmlall':'UTF-8'}</div>
                    <div class="custom-item-description">{$item['description']|escape:'htmlall':'UTF-8' nofilter}</div>
                </li>
            {/foreach}
        </ul>
    </div>
{/if}