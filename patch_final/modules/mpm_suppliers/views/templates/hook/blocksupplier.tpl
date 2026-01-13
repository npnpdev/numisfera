<div id="suppliers_block_left" class="block blocksupplier block-left-column">
	<div onclick="" class="title_block title-block-left-column">
		<a href="{$link|escape:'htmlall':'UTF-8'}" title="{l s='Suppliers' mod='mpm_suppliers'}">{l s='Suppliers' mod='mpm_suppliers'}</a>
	</div>
	<div class="left-column-arrows">
		<span class="column-arrows-add active"><i class="material-icons">add</i></span>
		<span class="column-arrows-remove"><i class="material-icons">remove</i></span>
	</div>
	<div class="block_content list-block content-block-left-column">
	{if $suppliers}
		<ul class="bullet">
			{foreach $suppliers as $supplier}
				<li class="item_supplie">
					<a href="{$supplier['link']|escape:'htmlall':'UTF-8'}" title="{l s='More about' mod='mpm_suppliers'} {$supplier.name|escape:'htmlall':'UTF-8'}"><i class="material-icons">keyboard_arrow_right</i>{$supplier.name|escape:'html':'UTF-8'}</a>
				</li>
			{/foreach}
		</ul>
	{/if}
	</div>
</div>

