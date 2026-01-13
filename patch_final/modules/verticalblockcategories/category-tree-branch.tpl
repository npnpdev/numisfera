{*
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA MILOSZ MYSZCZUK VATEU: PL9730945634
 * @copyright 2010-2025 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
 *}

<li class="category_{$node.id}{if isset($last) && $last == 'true'} last{/if}">

	<a href="{$node.link|escape:'html':'UTF-8'}" {if isset($currentCategoryId) && $node.id == $currentCategoryId}class="selected"{/if} {if $ProductPageMainCategory != false && $isDhtml}{if $ProductPageMainCategory == $node.id}class="selected"{/if}{/if} {if $ProductPageMainCategoryParents != false && $isDhtml}{if in_array($node.id, $ProductPageMainCategoryParents)} class="selected" {/if}{/if}
	   title="{$node.desc|strip_tags|trim|truncate:255:'...'|escape:'html':'UTF-8'}">{if Configuration::get('VC_IMG') == 1 && $node.image != ''}<img src="{$node.image}" class="vertical_category_thumb"/>{/if} <span class="vertical_category_name">{$node.name|escape:'html':'UTF-8'}</span></a>
	{if $node.children|@count > 0}
		<ul>
		{foreach from=$node.children item=child name=categoryTreeBranch}
			{if $smarty.foreach.categoryTreeBranch.last}
				{include file="$branche_tpl_path" node=$child last='true'}
			{else}
				{include file="$branche_tpl_path" node=$child last='false'}
			{/if}
		{/foreach}
		</ul>
	{/if}
</li>
