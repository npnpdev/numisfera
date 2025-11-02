  {if $settings['show_search']}
    <div class="block block_search block-left-column">
      <div class="title_block title-block-left-column">{l s='Search on Blog'  mod='mpm_blog'}</div>
      <div class="left-column-arrows">
        <span class="column-arrows-add active"><i class="material-icons">add</i></span>
        <span class="column-arrows-remove"><i class="material-icons">remove</i></span>
      </div>
      <div class="search_blog block_content content-block-left-column">
        <input type="text" class="search_blog_input search_query ac_input" placeholder="Search"/>
        <button onclick="searchBlog($('.search_blog_input').val(), '{$blogUrl|escape:'htmlall':'UTF-8'}'); return false;" class="search_blog_button">
          <i class="material-icons search">search</i>
        </button>
      </div>
    </div>
  {/if}
  {if $settings['show_categories']}
    <div class="block block_categories block-left-column">
      <div class="title_block title-block-left-column">{l s='Blog Categories'  mod='mpm_blog'}</div>
      <div class="left-column-arrows">
        <span class="column-arrows-add active"><i class="material-icons">add</i></span>
        <span class="column-arrows-remove"><i class="material-icons">remove</i></span>
      </div>
      <div class="category_blog block_content list-block content-block-left-column">
        <ul class="categories">
          {foreach from=$blogCat item=category}
            <li>
              <a class="category_name change_item" href="{$blogUrl|escape:'htmlall':'UTF-8'}{$category['link_rewrite']|escape:'htmlall':'UTF-8'}">{$category['name']|escape:'htmlall':'UTF-8'}</a>
              <span class="count_children">{$category['count']|escape:'htmlall':'UTF-8'}</span>
            </li>
          {/foreach}
        </ul>
      </div>
    </div>
  {/if}
  {if isset($settings['show_archive']) && $settings['show_archive']}
    <div class="block block_archive block-left-column">
      <div class="title_block title-block-left-column">{l s='Blog Archive'  mod='mpm_blog'}</div>
      <div class="left-column-arrows">
        <span class="column-arrows-add active"><i class="material-icons">add</i></span>
        <span class="column-arrows-remove"><i class="material-icons">remove</i></span>
      </div>
      <div class="archive_blog block_content list-block content-block-left-column">
        <ul class="archive">
          {foreach from=$archives item=archive}
            <li>
              <a class="archive_name change_item" href="{$blogUrl|escape:'htmlall':'UTF-8'}archive/{$archive['rez']|escape:'htmlall':'UTF-8'}"><i class="material-icons">&#xE2C7;</i><span>{$archive['date']|escape:'htmlall':'UTF-8'}</span></a>
              <span class="count_children">{$archive['count']|escape:'htmlall':'UTF-8'}</span>
            </li>
          {/foreach}
        </ul>
      </div>
    </div>
  {/if}
  {if isset($settings['show_tags']) && $settings['show_tags'] && count($most_tags)}
    <div class="block block_tags block-left-column">
      <div class="title_block title-block-left-column">{l s='Tags'  mod='mpm_blog'}</div>
      <div class="left-column-arrows">
        <span class="column-arrows-add active"><i class="material-icons">add</i></span>
        <span class="column-arrows-remove"><i class="material-icons">remove</i></span>
      </div>
      <div class="tags_blog block_content list-block content-block-left-column">
        <div class="tags">
          {foreach from=$most_tags key=key item=tags}
            <span onclick="searchBlog('{$tags|escape:'htmlall':'UTF-8'}', '{$blogUrl|escape:'htmlall':'UTF-8'}'); return false;" class="tag_{$key|escape:'htmlall':'UTF-8'}">{$tags|escape:'htmlall':'UTF-8'}</span>
          {/foreach}
          <div style="clear: both"></div>
        </div>
      </div>
    </div>
  {/if}
  {if isset($settings['featured_posts']) && $settings['featured_posts'] && count($featured)}
    <div class="block block_featured block-left-column">
      <div class="title_block title-block-left-column" id="title_block_blog">
        {l s='Featured articles'  mod='mpm_blog'}
        <span class="block_featured_arrows block_featured_arrows_base">
          <span class="bx-prev-blog" ><i class="material-icons">&#xE316;</i></span>
          <span class="bx-next-blog" ><i class="material-icons">&#xE313;</i></span>
        </span>
      </div>
      <div class="left-column-arrows">
        <span class="column-arrows-add active"><i class="material-icons">add</i></span>
        <span class="column-arrows-remove"><i class="material-icons">remove</i></span>
      </div>

      <div class="block_content  featured_blog" data-slides="{$settings['number_featured_posts']|escape:'htmlall':'UTF-8'}" data-count-slides="{count($featured)|escape:'htmlall':'UTF-8'}" >
        <span class="block_featured_arrows block_featured_arrows_size">
          <span class="bx-prev-blog" ></span>
          <span class="bx-next-blog" ></span>
        </span>
        <ul class="featured content-block-left-column">
          {foreach from=$featured item=value}
            <li>
              <div class="featured_cont">
                <span class="featured_name change_item" >
                  <span class="date_add_post">{$value['date_add']|escape:'htmlall':'UTF-8'}</span>
                  {if $value['is_image']}
                    <a href="{$blogUrl|escape:'htmlall':'UTF-8'}{$value['link_rewrite_category']|escape:'htmlall':'UTF-8'}/{$value['link_rewrite']|escape:'htmlall':'UTF-8'}.html">
                      <img alt="{$value['name']|truncate:40|escape:'htmlall':'UTF-8'}" class="featured_image" src="{$value['is_image']|escape:'htmlall':'UTF-8'}{$value['id_blog_post']|escape:'htmlall':'UTF-8'}-image_featured.jpg" />
                    </a>
                  {/if}
                  <a class="title_featured" href="{$blogUrl|escape:'htmlall':'UTF-8'}{$value['link_rewrite_category']|escape:'htmlall':'UTF-8'}/{$value['link_rewrite']|escape:'htmlall':'UTF-8'}.html">
                    {$value['name']|truncate:90|escape:'htmlall':'UTF-8'}
                  </a>
                </span>
              </div>
            </li>
          {/foreach}
        </ul>
      </div>
    </div>
  {/if}