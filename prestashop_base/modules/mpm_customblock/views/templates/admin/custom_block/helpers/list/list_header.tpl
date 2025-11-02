{extends file="helpers/list/list_header.tpl"}
{block name=leadin}
    {if isset($form) && $form}
        {html_entity_decode($form|escape:'htmlall':'UTF-8')}
    {/if}
{/block}
