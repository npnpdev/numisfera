<style>
.btn-primary-gomakoil{
    background: {if isset($background) && $background}{$background|escape:'htmlall':'UTF-8'}{/if} !important;
}

    .pagination .page-list li:hover,
    .btn-secondary:hover{
        -webkit-box-shadow: 0 0 0 35px {if isset($background) && $background}{$background|escape:'htmlall':'UTF-8'}{/if} inset;
        -moz-box-shadow: 0 0 0 35px {if isset($background) && $background}{$background|escape:'htmlall':'UTF-8'}{/if} inset;
        box-shadow: 0 0 0 35px {if isset($background) && $background}{$background|escape:'htmlall':'UTF-8'}{/if} inset;
    }

    #product .tabs .nav-tabs .nav-link:hover,
    #product .tabs .nav-tabs .nav-link.active,
    .pagination .page-list li:hover a,
    #products.grid .quick-view:hover,
    .products-sort-order .select-list:hover,
    .btn-primary-gomakoil:hover,
    .btn-secondary:hover{
        color: {if isset($background_hover) && $background_hover}{$background_hover|escape:'htmlall':'UTF-8'}{/if} !important;
    }


    .save_new_comment:hover,
    .send_contact_form_message:hover,
    #products.list .product-add-to-cart .add_cart_brandfashion.add_cart_brandfashion:hover,
    #products.list .quick-view:hover,
    .productsBlock  .add_cart_brandfashion:hover,
	 .btn-primary-gomakoil:hover,
    .js-search-filters-clear-all:hover{
        -webkit-box-shadow: 0 0 0 35px {if isset($background_hover) && $background_hover}{$background_hover|escape:'htmlall':'UTF-8'}{/if} inset;
        -moz-box-shadow: 0 0 0 35px {if isset($background_hover) && $background_hover}{$background_hover|escape:'htmlall':'UTF-8'}{/if} inset;
        box-shadow: 0 0 0 35px {if isset($background_hover) && $background_hover}{$background_hover|escape:'htmlall':'UTF-8'}{/if} inset;
    }


    /*** BORDER ***/

    #search_filters .facet.active,
    #search_filters .facet:hover,
    #product-availability,
    #product .tabs,
    #product .tabs .nav-tabs,
    .send_contact_form_message,
    .save_new_comment,
    #products.list .quick-view,
    #products.list  .add_cart_brandfashion,
    .pagination .page-list li,
    .content-block-left-column ul li:hover,
    .productsBlock  .add_cart_brandfashion, .js-search-filters-clear-all,
    .btn-primary-gomakoil,
    .home_page_articles_title span:before,
    .title_manufacturer_block .title span:before,
    .title_supplier_block .title span:before,
    .header_featured_slider span:before,
    .home_page_articles_title span:after,
    .title_manufacturer_block .title span:after,
    .title_supplier_block .title span:after,
    .header_featured_slider span:after,
    .btn-secondary,
    .btn-primary,
    #block_left_menu .level_depth_2.mobive_menu:hover,
    .banner-slider .banner-item:hover .banner-item-img,
    .product-images-modal img.selected,
    .product-images-modal img:hover,
    .top_menu.mobile_device,
    .top_menu.mobile_device .top_menu_one a.item_top_menu,
    #products.list .thumbnail-container:hover,
    #home_page_menu_blog li:hover img,
    .subcategories_item:hover .subcategory-image,
    .material-icons-phone,
    #product .tabs_block .nav.nav-tabs,
    #product .tabs_block .tabs .nav-tabs .nav-link.active,
    .thumb-quickview-container.selected,
    .thumb-quickview-container:hover,
    #suppliers_block_left .block_content li:hover,
    #manufacturers_block_left .block_content li:hover,
    #products.grid .thumbnail-container:hover,
    .featured-products .thumbnail-container:hover,
    .product-accessories .thumbnail-container:hover,
    .product-category .thumbnail-container:hover,
    .subcategories_level_depth_2,
    #block_left_menu .level_depth_2.active,
    .supplier-block .supplier-item:hover .img_block_supplier,
    .manufacturer-block .manufacturer-item:hover .img_block_manufacturer,
    .homepage_tabs,
    .top_menu_content .block{
        border-color: {if isset($background) && $background}{$background|escape:'htmlall':'UTF-8'}{/if} !important;
    }

    /*** BACKGROUND ***/

    #product .tabs .nav-tabs .nav-link:hover,
    #product .tabs .nav-tabs .nav-link.active,
    .send_contact_form_message,
    .save_new_comment,
    #products.list .quick-view,
    #products.list  .add_cart_brandfashion,
    .productsBlock  .add_cart_brandfashion,
    .js-search-filters-clear-all,
    .productsBlock  .add_cart_brandfashion, .js-search-filters-clear-all,
    #products.grid  .add_cart_brandfashion,
    #products.grid .quick-view,
    .input-group .input-group-btn > .btn,
    .top_menu.mobile_device .item_top_menu.active,
    .scroll_top_block .scroll_top,
    #products.grid .up .btn-secondary,
    #products.grid .up .btn-tertiary,
    #products.list .highlighted-informations .quick-view:hover,
    #search_filters .search_filters_title,
    .product-cover .layer:hover .zoom-in,
    .one_field_form_attach label.btn.btn-default,
    .button_freecall button,
    .header_freecall .close_form_freecall:hover,
    .footer_freecall button,
    .button_question a,
    .btn-primary.disabled:hover,
    .btn-primary:disabled:hover,
    .btn-primary:focus,
    .btn-primary.focus,
    .custom-radio input[type="radio"]:checked + span,
    body#checkout section.checkout-step .step-number,
    body#checkout section.checkout-step.-current.-reachable.-complete .step-number,
    #product .tabs_block .tabs .nav-tabs .nav-link.active,
    .products-sort-order .select-list:hover,
    .pagination a:hover,
    .pagination .current a,
    #search_filters .search_filters_title,
    .footer_freecall button:hover,
    .button_question a,
    .homepage_tabs .tab_featured.active a,
    .homepage_tabs .tab_featured a:hover,
    .top_menu .links_menu div:hover,
    #products.grid .highlighted-informations .quick-view:hover,
    .featured-products .highlighted-informations .quick-view:hover,
    .product-accessories .highlighted-informations .quick-view:hover,
    .product-category .highlighted-informations .quick-view:hover,
    .block_newsletter form input.btn-primary,
    .footer_soc_button li:hover,
    .tracker-individual-container .tracker-individual-blip-selected,
    .tracker-individual-container .tracker-individual-blip:hover,
    .top_menu_one:hover a.item_top_menu,
    .search_widget button,
    .search_widget button,
    .pozvonim-button .block_button .title_callback,
    .pozvonim-button .block_inform:before{
        background-color: {if isset($background) && $background}{$background|escape:'htmlall':'UTF-8'}{/if} !important;
    }

    /*** COLOR ***/
    .user-info .account i,
    #search_filters .facet .facet-title.active,
    #search_filters .facet .facet-title:hover,
    .product-quantities-prod span,
    .product-reference-prod span,
    #product-availability,
    .featured_name:hover a,
    .save_new_comment:hover,
    .send_contact_form_message:hover,
    #products.list .product-add-to-cart .add_cart_brandfashion.add_cart_brandfashion:hover,
    #products.list .quick-view:hover,
    .block-categories .arrows .arrow-down:hover,
    .block-categories .arrows .arrow-right:hover,
    .topMenuBlock .product-price-and-shipping,
    .productsBlock  .add_cart_brandfashion:hover,
    .js-search-filters-clear-all:hover,
    .btn-primary-gomakoil:hover,
    .item_articles .date_add,
    .block_newsletter .btn-primary:hover,
    ._desktop_search_icon.active i,
    .search_close:hover i,
    .search-widget form button[type=submit] .search:hover,
    .item_block_title,
    .homecontacts_phone .phone_icon i,
    .homecontacts_email .email_icon i,
    .homecontacts_working_days .working_days_icon i,
    #_desktop_user_info:hover i,
    ._desktop_search_icon:hover i,
    .header:hover i,
    .btn-secondary,
    .btn-primary:hover,
    .display_list_grid li a:hover,
    .display_list_grid li.selected a,
    .display_list_grid li a:hover,
    .display_list_grid li.selected a,
    .banner-slider .banner-item:hover .title,
    #home_page_menu_blog li:hover .link-blog-home,
    .supplier-block .supplier-item:hover .title,
    .manufacturer-block .manufacturer-item:hover .title,
    #products.grid .product-title a:hover,
    #products.list .product-title a:hover,
    .product-title-hidden a:hover,
    .page-my-account #content .links a:hover,
    .sortPagiBarBlog ul li.selected i,
    .sortPagiBarBlog ul li:hover i,
    .subcategories_item:hover .subcategory-name span,
    .block_description_front .cont_column_form .material-icons,
    .footer_freecall i,
    #wrapper .breadcrumb li a:hover,
    .featured_blog .title_featured:hover,
    .block.block_categories a:hover,
    .block.block_tags a:hover,
    .block.block_archive a:hover,
    .tracker-individual-container .tracker-individual-blip-selected,
    .tracker-individual-container .tracker-individual-blip:hover,
    .page-my-account #content .links a:hover i,
    #suppliers_block_left .block_content li:hover a,
    #suppliers_block_left .block_content li:hover a:before,
    #manufacturers_block_left .block_content li:hover a,
    #manufacturers_block_left .block_content li:hover a:before,
    #search_filters .facet .facet-label a span,
    #_desktop_search_filters_clear_all button:hover,
    .footer_freecall .icon i,
    .subcategories_level_depth_2 .categories_block_left_menu a:hover,
    .links_left_menu a:hover,
    .links_left_menu a:hover:before,
    #block_left_menu.block .item_level_depth_2:hover,
    #block_left_menu.block .active .item_level_depth_2,
    .block-contact i,
    a:hover,
    .footer-container li a:hover,
    .arrow_top,
    .arrow_bottom,
    .arrow_top:hover,
    .arrow_bottom:hover,
    .dropdown:hover .expand-more,
    #_desktop_currency_selector a:hover,
    #_desktop_language_selector a:hover,
    #_desktop_user_info a:hover,
    #_desktop_search_icon a:hover,
    #_desktop_cart a:hover{
        color: {if isset($background) && $background}{$background|escape:'htmlall':'UTF-8'}{/if} !important;
    }

    /*** HOVER ***/


    .one_field_form_attach label.btn.btn-default:hover,
    .button_freecall button:hover,
    .button_question a:hover,
    .header_freecall .close_form_freecall:hover,
    .footer_freecall button:hover,
    .button_question a:hover,
    .block_newsletter form input.btn-primary:hover,
    .search_widget button:hover,
    .pozvonim-button .block_button .title_callback:hover{
        background-color: {if isset($background_hover) && $background_hover}{$background_hover|escape:'htmlall':'UTF-8'}{/if} !important;
    }


    /*** BUTTON ***/

    .cart-content-button a,
    .btn-primary{
        background-color: {if isset($background) && $background}{$background|escape:'htmlall':'UTF-8'}{/if} !important;
    }

    /*** BUTTON HOVER***/

    .btn-primary:hover{
        background-color: {if isset($background_hover) && $background_hover}{$background_hover|escape:'htmlall':'UTF-8'}{/if} !important;
    }

    /*** HEADER ***/

    #header .header-nav{
        border-top-color: {if isset($background) && $background}{$background|escape:'htmlall':'UTF-8'}{/if}  !important;
    }

    /*** CONTENT ***/

    #header,
    main,
    #header .header-top,
    #wrapper{
        background-color: {if isset($page) && $page}{$page|escape:'htmlall':'UTF-8'}{/if};
    }

    .product-price,
    .cart-summary-line.cart-total .value,
    .cart-summary-line#cart-subtotal-products .value,
    #cart .cart-item .product-price,
    .current-price,
    .top_menu .price,
    #block_left_menu .price,
    #products .product-price-and-shipping,
    .featured-products .product-price-and-shipping,
    .product-category .product-price-and-shipping
    .product-accessories .product-price-and-shipping
    {
        color: {if isset($background) && $background}{$background|escape:'htmlall':'UTF-8'}{/if} !important;
    }


</style>
