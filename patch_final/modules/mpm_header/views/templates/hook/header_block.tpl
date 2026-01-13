
{block name='header_top'}
    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="_desktop_logo">
                    <a href="{$urls.base_url|escape:'htmlall':'UTF-8'}">
                        <img class="logo img-responsive" src="{$shop.logo|escape:'htmlall':'UTF-8'}" alt="{$shop.name|escape:'htmlall':'UTF-8'}">
                    </a>
                </div>
                <div class="_desktop_header">

                    {*{widget name="ps_contactinfo" hook="displayNav1"}*}

                    {widget name="ps_shoppingcart" hook="displayNav1"}
                    <div class="_desktop_search_icon"><i class="material-icons">search</i></div>
                    {widget name="ps_customersignin" hook="displayNav1"}
                    {widget name="ps_languageselector"}
                    {widget name="ps_currencyselector"}
                    {hook h='displayTopMenu'}

                </div>
                <div class="_desktop_hook_top">
                    {hook h='displayTop'}
                </div>
            </div>
        </div>
    </div>
    {hook h='displayNavFullWidth'}
{/block}


