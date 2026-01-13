<div class="productsBlockFeatured">
    <div id="product-tab-content-wait" style="display:none">
        <div id="loading"><i class="icon-refresh icon-spin"></i>&nbsp;{l s='Loading...' mod='mpm_homefeatured'}</div>
    </div>
    <div class="panel form-horizontal">
        <div class="panel-heading">
            <i class="icon-plus-sign-alt"></i> {l s='ADD PRODUCT' mod='mpm_homefeatured'}
        </div>
        <div class="form-wrapper">
            <div class="form-group">
                <div id="control-label col-lg-3">

                    <div class="add_new_product_block">
                        <div style="float: left">
                            <input id="attendee_home" name="AttendeeId" type="text" value="" placeholder="{l s='Search for a product' mod='mpm_homefeatured'}"/>
                        </div>
                        <div class="col-lg-2 product-pack-button-menu">
                            <button type="button" id="add_products_item_featured" class="btn btn-default">
                                <i class="icon-plus-sign-alt"></i> {l s='Add this product' mod='mpm_homefeatured'}
                            </button>
                        </div>
                    </div>
                    <div class="form-wrapper added_products">
                        {html_entity_decode($content|escape:'htmlall':'UTF-8')}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>