
<div class="homeBanner">
    <ul class="homeBannerContent" >
        {foreach $settings as $value}
            <li class="item_block_{$value['id_homeblocks']|escape:'htmlall':'UTF-8'}" style='background: url("{$value['image']|escape:'htmlall':'UTF-8'}"); width: {$value['width']|escape:'htmlall':'UTF-8'}%; min-height: {$value['min_height']|escape:'htmlall':'UTF-8'}px; background-color:{$value['background_color']|escape:'htmlall':'UTF-8'} '>{$value['description']|escape:'htmlall':'UTF-8' nofilter}</li>
        {/foreach}
    </ul>

</div>