<style type="text/css">

    .item_menu_{$value['id_topmenu']|escape:'htmlall':'UTF-8'} a.item_menu_link{
        background-color: {$value['background_color_tab']|escape:'htmlall':'UTF-8'};
        color: {$value['text_color_tab']|escape:'htmlall':'UTF-8'} !important;
    }
    .item_menu_{$value['id_topmenu']|escape:'htmlall':'UTF-8'}:hover a.item_menu_link{
        color: {$value['text_color_hover_tab']|escape:'htmlall':'UTF-8'} !important;
        background-color: {$value['background_color_hover_tab']|escape:'htmlall':'UTF-8'};
    }

    .item_menu_{$value['id_topmenu']|escape:'htmlall':'UTF-8'} .subcat_menu{
        background-color: {$value['background_color']|escape:'htmlall':'UTF-8'};
        border: {$value['border_size']|escape:'htmlall':'UTF-8'}px solid {$value['border_color']|escape:'htmlall':'UTF-8'};

        {if $value['width'] > 0}
            width: {$value['width']|escape:'htmlall':'UTF-8'}px;
        {else}
            width: 100%;
        {/if}

        min-height: {$value['min_height']|escape:'htmlall':'UTF-8'}px;
    }
    .item_menu_{$value['id_topmenu']|escape:'htmlall':'UTF-8'} .categories-block-arrows i{
        color: {$value['text_color_tab']|escape:'htmlall':'UTF-8'} !important;
    }

</style>