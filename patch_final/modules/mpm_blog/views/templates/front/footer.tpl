<div class="col-md-2 links links_blog">
    <h3 class="h3 hidden-sm-down">{l s='Latest articles'  mod='mpm_blog'}</h3>
    <div class="title clearfix hidden-md-up" data-target="#footer_sub_menu_blog" data-toggle="collapse">
        <span class="h3">{l s='Latest blog articles'  mod='mpm_blog'}</span>
        <span class="pull-xs-right">
          <span class="navbar-toggler collapse-icons">
            <i class="material-icons add">keyboard_arrow_down</i>
            <i class="material-icons remove">keyboard_arrow_up</i>
          </span>
        </span>
    </div>
    <ul id="footer_sub_menu_blog" class="collapse">
        {foreach from=$articles item=value}
            <li>
                <a class="link-blog cms-page-link" href="{$blogUrl|escape:'htmlall':'UTF-8'}{$value['link_rewrite_category']|escape:'htmlall':'UTF-8'}/{$value['link_rewrite']|escape:'htmlall':'UTF-8'}.html" >
                    {$value['name']|truncate:90|escape:'htmlall':'UTF-8'}
                </a>
            </li>
        {/foreach}
    </ul>
</div>