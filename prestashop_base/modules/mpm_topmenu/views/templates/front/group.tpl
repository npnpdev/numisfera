
{if isset($group) && $group}
    <div class="content_group">
        {if isset($group['title_front']) && $group['title_front']}
            <div class="title_group">{$group['title_front']|escape:'htmlall':'UTF-8'}</div>
        {/if}
        <div class="content_item_group">{$group['tpl']|escape:'htmlall':'UTF-8' nofilter}</div>
        {if isset($group['content']) && $group['content']}
            <div class="content_item_group">{$group['content']|escape:'htmlall':'UTF-8' nofilter}</div>
        {/if}
    </div>
{/if}

