
<div class="footer-container">
    <div class="container">
        <div class="row displayFooterBefore">
            <div class="_desktop_logo">
                <a href="{$urls.base_url|escape:'htmlall':'UTF-8'}">
                    <img class="logo img-responsive" src="{$shop.logo|escape:'htmlall':'UTF-8'}" alt="{$shop.name|escape:'htmlall':'UTF-8'}">
                </a>
            </div>
            {hook h='displayFooterBefore'}
        </div>
        <div class="row">
            {hook h='displayFooter'}
        </div>
        <div class="row">
            {hook h='displayFooterAfter'}
        </div>
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="_blank" href="http://www.prestashop.com" target="_blank">
                        {l s='©' mod='mpm_footer'} {date('Y')|escape:'htmlall':'UTF-8'} {l s=' - Ecommerce software by PrestaShop™' mod='mpm_footer'}
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    {if isset($image) && $image}
    .footer-container{
        background: url("{$image|escape:'htmlall':'UTF-8'}");
    }
    {/if}

    {if isset($background_color) && $background_color}
        .footer-container{
            background-color: {$background_color|escape:'htmlall':'UTF-8'};
            color: {$color|escape:'htmlall':'UTF-8'};
        }
    {/if}

    .footer-container h1,
    .footer-container h2,
    .footer-container h3,
    .footer-container h4,
    .footer-container h5,
    .footer-container div,
    .footer-container p,
    .footer-container li a,
    .footer-container a{

    {if isset($color) && $color}
        color: {$color|escape:'htmlall':'UTF-8'} !important;
     {else}
        color: #ffffff !important;
    {/if}
    }

    .footer-container li a:hover{
        {if isset($hover) && $hover}
            color: {$hover|escape:'htmlall':'UTF-8'} !important;
        {else}
            color: #d19e65 !important;
        {/if}
    }

</style>