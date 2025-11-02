{if $suppliers}
    <div class="container_supplier">
        <div class="title_supplier_block">
            <div class="title"><span>{l s='Suppliers' mod='mpm_suppliers'}</span></div>
        </div>
        <div class="supplier-block">
            <ul class="supplier-list-homepage supplier-slider" data-count="{count($suppliers)|escape:'html':'UTF-8'}">
                {foreach from=$suppliers key=key item=supplier}
                    <li class="supplier-item">
                        <a href="{$supplier['link']|escape:'htmlall':'UTF-8'}" title=" ">
                            <span class="img_block_supplier">
                                <img src="{$supplier['image']|escape:'htmlall':'UTF-8'}" alt="{$supplier.name|truncate:40:'...':true|escape:'html':'UTF-8'}">
                            </span>
                            {if $title}
                                <span class="title">{$supplier.name|truncate:40:'...':true|escape:'html':'UTF-8'}</span>
                            {/if}
                        </a>
                    </li>
                {/foreach}
            </ul>
        </div>
    </div>
{/if}