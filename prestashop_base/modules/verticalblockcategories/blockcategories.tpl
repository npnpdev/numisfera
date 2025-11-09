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
 
{if $blockCategTree && $blockCategTree.children|@count}
<!-- Block categories module -->
<div id="categories_block_left" class="block verticalblockcategories">
	<h2 class="title_block">
		{if isset($currentCategory) && Configuration::get('BLOCK_CATEG_ROOT_CATEGORY') != 0}
			{$currentCategory->name|escape}
		{else}
			{l s='Categories' mod='blockcategories'}
		{/if}
	</h2>
	<div class="block_content vertical">
		<ul class="tree">
			{foreach from=$blockCategTree.children item=child name=blockCategTree}
				{if $smarty.foreach.blockCategTree.last}
					{include file="$branche_tpl_path" node=$child last='true'}
				{else}
					{include file="$branche_tpl_path" node=$child}
				{/if}
			{/foreach}
		</ul>
	</div>
</div>
<!-- /Block categories module -->
{/if}
