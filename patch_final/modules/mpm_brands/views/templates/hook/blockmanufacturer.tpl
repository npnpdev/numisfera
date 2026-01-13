<div id="manufacturers_block_left" class="block blockmanufacturer block-left-column">

	<div onclick="" class="title_block title-block-left-column"> <a href="{$link|escape:'htmlall':'UTF-8'}" title="{l s='Brands' mod='mpm_brands'}">{l s='Brands' mod='mpm_brands'}</a> </div>
	<div class="left-column-arrows">
		<span class="column-arrows-add active"><i class="material-icons">add</i></span>
		<span class="column-arrows-remove"><i class="material-icons">remove</i></span>
	</div>
	<div class="block_content list-block content-block-left-column">
		{if $manufacturers}
			<ul class="bullet ">
				{foreach $manufacturers as $manufacturer}
					<li class="manufacturer_item"><a href="{$manufacturer['link']|escape:'htmlall':'UTF-8'}" title="{l s='More about %s' sprintf=[$manufacturer.name|escape:'htmlall':'UTF-8'] mod='mpm_brands'}"><i class="material-icons">keyboard_arrow_right</i>{$manufacturer.name|escape:'html':'UTF-8'}</a></li>
				{/foreach}
			</ul>

		{/if}
	</div>
</div>

