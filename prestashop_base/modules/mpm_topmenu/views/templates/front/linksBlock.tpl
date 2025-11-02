<ul class="links_menu">
    {foreach from=$links key=key item=item}
        <li>
            <a href="{$item['link']|escape:'htmlall':'UTF-8'}">{$item['title']|escape:'htmlall':'UTF-8'}</a>
        </li>
    {/foreach}
    <li style="clear: both"></li>
</ul>
<div style="clear: both"></div>