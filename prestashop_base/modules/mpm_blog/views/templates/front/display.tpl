{extends file='page.tpl'}

{block name='left_column'}
  <div id="left-column" class="col-xs-12 col-sm-4 col-md-3">
    {widget name="mpm_blog" hook='displayLeftColumn'}
  </div>
{/block}

{block name='page_content'}
  <div class="center_column_blog">
   {if isset($index_page_block) && $index_page_block && $index_page_block['active']}
     <div class="index_page_block">
         {if isset($index_page_block['images']) && $index_page_block['images']}
             <div class="index_page_block_img">
                 <img src="{$index_page_block['images']|escape:'htmlall':'UTF-8'}">
             </div>
         {/if}
         {if isset($index_page_block['description']) && $index_page_block['description']}
             <div class="index_page_block_description">
                 {$index_page_block['description']|escape:'htmlall':'UTF-8' nofilter}
             </div>
         {/if}
     </div>
   {/if}

   {if (isset($category_page_block['description']) && $category_page_block['description'])  || $category_page_block['image']}
     <div class="category_page_block">
         {if $category_page_block['image']}
             <div class="category_page_block_img">
                 <img src="{$category_page_block['image']|escape:'htmlall':'UTF-8'}">
             </div>
         {/if}
         {if isset($category_page_block['description']) && $category_page_block['description'] }
             <div class="category_page_block_description">
                 {$category_page_block['description']|escape:'htmlall':'UTF-8' nofilter}
             </div>
         {/if}
     </div>
   {/if}

    {if isset($posts) && $posts}
      <div id="content_post" class="content_post list  ">
        <div class="sortPagiBarBlog">
          <ul>
            <li class="display-title"><span>{l s='View:'  mod='mpm_blog'}</span></li>
            <li id="grid"><a  rel="nofollow"  title="{l s='Grid'  mod='mpm_blog'}"><i class="material-icons">&#xE8F0;</i><span>{l s='Grid'  mod='mpm_blog'}</span></a></li>
            <li id="list" class="selected"><a  rel="nofollow"  title="{l s='List'  mod='mpm_blog'}"><i class="material-icons">&#xE8EF;</i><span>{l s='List'  mod='mpm_blog'}</span></a></li>
          </ul>
        </div>
        <div style="clear: both"></div>
        {foreach from=$posts key=key item=posts_item}
          <div class="one_post {if $key % 2 == 0}even{/if}">
            <div class="header_post">
              <div class="header_post_date">
                <span class="date_y">{$posts_item['date_y']|escape:'htmlall':'UTF-8'}</span>
                <span class="date_d">{$posts_item['date_d']|escape:'htmlall':'UTF-8'}</span>
                <span class="date_m">{$posts_item['date_m']|escape:'htmlall':'UTF-8'}</span>
              </div>
              <div id="header_post_url">
                <a href="{$blogUrl|escape:'htmlall':'UTF-8'}{$posts_item['link_rewrite_category']|escape:'htmlall':'UTF-8'}/{$posts_item['link_rewrite']|escape:'htmlall':'UTF-8'}.html" class="header_post_url">{$posts_item['name']|escape:'htmlall':'UTF-8'}</a>
              </div>
              <div style="clear: both"></div>
            </div>
            <div class="content_post_row">
              {if $posts_item['is_image']}
                <a href="{$blogUrl|escape:'htmlall':'UTF-8'}{$posts_item['link_rewrite_category']|escape:'htmlall':'UTF-8'}/{$posts_item['link_rewrite']|escape:'htmlall':'UTF-8'}.html" class="header_post_url">
                  <img class="list_image" src="{$posts_item['is_image']|escape:'htmlall':'UTF-8'}{$posts_item['id_blog_post']|escape:'htmlall':'UTF-8'}-image_list.jpg" />
                </a>
                <a href="{$blogUrl|escape:'htmlall':'UTF-8'}{$posts_item['link_rewrite_category']|escape:'htmlall':'UTF-8'}/{$posts_item['link_rewrite']|escape:'htmlall':'UTF-8'}.html" class="header_post_url">
                  <img class="grid_image" src="{$posts_item['is_image']|escape:'htmlall':'UTF-8'}{$posts_item['id_blog_post']|escape:'htmlall':'UTF-8'}-image_grid.jpg" />
                </a>
                {/if}
              {$posts_item['description_short']|escape:'htmlall':'UTF-8' nofilter}
              <div style="clear: both"></div>
            </div>
            <div class="footer_post">
              {if isset($posts_item['allow_comment_category']) && isset($posts_item['allow_comment']) && $posts_item['allow_comment_category'] && $posts_item['allow_comment'] && $settings['use_comments']}
                <a class="footer_post_comments"><i class="material-icons">&#xE0B9;</i><span>{$posts_item['rating_count']|escape:'htmlall':'UTF-8'} {l s='Comments'  mod='mpm_blog'}</span><div class="clear:both"></div></a>
              {/if}
              <a class="footer_post_rating"><i class="material-icons">star_rate</i><span>{l s='Article rating'  mod='mpm_blog'} {if $posts_item['rating']} {round($posts_item['rating'], 1)|escape:'htmlall':'UTF-8'} {else} 0 {/if}</span><div class="clear:both"></div></a>
            </div>
            <div style="clear: both"></div>
          </div>
        {/foreach}

      </div>
      {if $start!=$stop}
        <div style="clear: "></div>
        <div id="blog_pagination">
          <ul class="blog_pagination">
            {if $start==3}
              <li>
                <a href="{$path_pagination|escape:'htmlall':'UTF-8'}1">
                  <span>1</span>
                </a>
              </li>
              <li>
                <a href="{$path_pagination|escape:'htmlall':'UTF-8'}2">
                  <span>2</span>
                </a>
              </li>
            {/if}
            {if $start==2}
              <li>
                <a href="{$path_pagination|escape:'htmlall':'UTF-8'}1">
                  <span>1</span>
                </a>
              </li>
            {/if}
            {if $start>3}
              <li>
                <a href="{$path_pagination|escape:'htmlall':'UTF-8'}1">
                  <span>1</span>
                </a>
              </li>
              <li class="truncate">
                <span>
                  <span>...</span>
                </span>
              </li>
            {/if}
            {section name=pagination start=$start loop=$stop+1 step=1}
              {if $p == $smarty.section.pagination.index}
                <li class="active current">
                  <span>
                    <span>{$p|escape:'html':'UTF-8'}</span>
                  </span>
                </li>
              {else}
                <li>
                  <a href="{$path_pagination|escape:'htmlall':'UTF-8'}{$smarty.section.pagination.index|escape:'htmlall':'UTF-8'}" >
                    <span>{$smarty.section.pagination.index|escape:'html':'UTF-8'}</span>
                  </a>
                </li>
              {/if}
            {/section}
            {if $pages_nb>$stop+2}
              <li class="truncate">
                <span>
                  <span>...</span>
                </span>
              </li>
              <li>
                <a href="{$path_pagination|escape:'htmlall':'UTF-8'}{$pages_nb|escape:'htmlall':'UTF-8'}">
                  <span>{$pages_nb|intval}</span>
                </a>
              </li>
            {/if}
            {if $pages_nb==$stop+1}
              <li>
                <a href="{$path_pagination|escape:'htmlall':'UTF-8'}{$pages_nb|escape:'htmlall':'UTF-8'}">
                  <span>{$pages_nb|intval}</span>
                </a>
              </li>
            {/if}
            {if $pages_nb==$stop+2}
              <li>
                <a href="{$path_pagination|escape:'htmlall':'UTF-8'}{($pages_nb-1)|escape:'htmlall':'UTF-8'}">
                  <span>{$pages_nb-1|intval}</span>
                </a>
              </li>
              <li>
                <a href="{$path_pagination|escape:'htmlall':'UTF-8'}{$pages_nb|escape:'htmlall':'UTF-8'}">
                  <span>{$pages_nb|intval}</span>
                </a>
              </li>
            {/if}
          </ul>
        </div>
      {/if}
    {/if}
    {if isset($post) && $post}
      <div class="content_post page">
        <div class="header_post">
          <div class="header_post_date">
            <span class="date_y">{$post['date_y']|escape:'htmlall':'UTF-8'}</span>
            <span class="date_d">{$post['date_d']|escape:'htmlall':'UTF-8'}</span>
            <span class="date_m">{$post['date_m']|escape:'htmlall':'UTF-8'}</span>
          </div>
          <div id="header_post_url">
            <h1>{$post['name']|escape:'htmlall':'UTF-8'}</h1>
          </div>
        </div>
        <div class="content_post_p">
          <div class="line_post">
            {if isset($post['allow_comment_category']) && isset($post['allow_comment']) && $post['allow_comment_category'] && $post['allow_comment'] && $settings['use_comments']}
              <a class="line_post_comments"><i class="material-icons">&#xE0B9;</i><span>{$post['rating_count']|escape:'htmlall':'UTF-8'} {l s='Comments'  mod='mpm_blog'}</span><div class="clear:both"></div></a>
            {/if}
            <a class="line_post_rating"><i class="material-icons">star_rate</i><span>{l s='Article rating'  mod='mpm_blog'} {if $post['rating']} {round($post['rating'], 1)|escape:'htmlall':'UTF-8'} {else} 0 {/if}</span><div class="clear:both"></div></a>
          </div>
          {$post['description']|escape:'htmlall':'UTF-8' nofilter}
          <div style="clear: both"></div>

        </div>

        <div class="rrssb-social-buttons" data-url="{$url|escape:'htmlall':'UTF-8'}" data-title="{$post['name']|escape:'htmlall':'UTF-8'}" data-description="{strip_tags($post['description'])|truncate:200:'...'|escape:'htmlall':'UTF-8'}" data-emailBody="{$email|escape:'htmlall':'UTF-8'}">

          {if $settings['show_social_button']}

            <ul class="rrssb-buttons rrssb-1">

              {if isset($settings['button_facebook']) && $settings['button_facebook']}
                <li class="rrssb-facebook" data-initwidth="6.25" data-size="68" style="width: calc(7.69231% - 9.69231px);">
                  <a href="" class="popup">
                  <span class="rrssb-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29 29">
                        <path d="M26.4 0H2.6C1.714 0 0 1.715 0 2.6v23.8c0 .884 1.715 2.6 2.6 2.6h12.393V17.988h-3.996v-3.98h3.997v-3.062c0-3.746 2.835-5.97 6.177-5.97 1.6 0 2.444.173 2.845.226v3.792H21.18c-1.817 0-2.156.9-2.156 2.168v2.847h5.045l-.66 3.978h-4.386V29H26.4c.884 0 2.6-1.716 2.6-2.6V2.6c0-.885-1.716-2.6-2.6-2.6z"></path>
                      </svg>
                  </span>
                    <span class="rrssb-text">{l s='facebook'  mod='mpm_blog'}</span>
                  </a>
                </li>
              {/if}

              {if isset($settings['button_twitter']) && $settings['button_twitter']}
                <li class="rrssb-twitter" data-initwidth="6.25" data-size="52" style="width: calc(7.69231% - 9.69231px);">
                  <a href="" class="popup">
                  <span class="rrssb-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28">
                        <path d="M24.253 8.756C24.69 17.08 18.297 24.182 9.97 24.62c-3.122.162-6.22-.646-8.86-2.32 2.702.18 5.375-.648 7.507-2.32-2.072-.248-3.818-1.662-4.49-3.64.802.13 1.62.077 2.4-.154-2.482-.466-4.312-2.586-4.412-5.11.688.276 1.426.408 2.168.387-2.135-1.65-2.73-4.62-1.394-6.965C5.574 7.816 9.54 9.84 13.802 10.07c-.842-2.738.694-5.64 3.434-6.48 2.018-.624 4.212.043 5.546 1.682 1.186-.213 2.318-.662 3.33-1.317-.386 1.256-1.248 2.312-2.4 2.942 1.048-.106 2.07-.394 3.02-.85-.458 1.182-1.343 2.15-2.48 2.71z"></path>
                      </svg>
                  </span>
                    <span class="rrssb-text">{l s='twitter'  mod='mpm_blog'}</span>
                  </a>
                </li>
              {/if}

              {if isset($settings['button_googleplus']) && $settings['button_googleplus']}
                <li class="rrssb-googleplus" data-initwidth="6.25" data-size="58" style="width: calc(7.69231% - 9.69231px);">
                  <a href="" class="popup">
                  <span class="rrssb-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M21 8.29h-1.95v2.6h-2.6v1.82h2.6v2.6H21v-2.6h2.6v-1.885H21V8.29zM7.614 10.306v2.925h3.9c-.26 1.69-1.755 2.925-3.9 2.925-2.34 0-4.29-2.016-4.29-4.354s1.885-4.353 4.29-4.353c1.104 0 2.014.326 2.794 1.105l2.08-2.08c-1.3-1.17-2.924-1.883-4.874-1.883C3.65 4.586.4 7.835.4 11.8s3.25 7.212 7.214 7.212c4.224 0 6.953-2.988 6.953-7.082 0-.52-.065-1.104-.13-1.624H7.614z"></path>
                      </svg>
                  </span>
                    <span class="rrssb-text">{l s='google+'  mod='mpm_blog'}</span>
                  </a>
                </li>
              {/if}

              {if isset($settings['button_linkedin']) && $settings['button_linkedin']}
                <li class="rrssb-linkedin" data-initwidth="6.25" data-size="57" style="width: calc(7.69231% - 9.69231px);">
                  <a href="" class="popup">
                  <span class="rrssb-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28">
                        <path d="M25.424 15.887v8.447h-4.896v-7.882c0-1.98-.71-3.33-2.48-3.33-1.354 0-2.158.91-2.514 1.802-.13.315-.162.753-.162 1.194v8.216h-4.9s.067-13.35 0-14.73h4.9v2.087c-.01.017-.023.033-.033.05h.032v-.05c.65-1.002 1.812-2.435 4.414-2.435 3.222 0 5.638 2.106 5.638 6.632zM5.348 2.5c-1.676 0-2.772 1.093-2.772 2.54 0 1.42 1.066 2.538 2.717 2.546h.032c1.71 0 2.77-1.132 2.77-2.546C8.056 3.593 7.02 2.5 5.344 2.5h.005zm-2.48 21.834h4.896V9.604H2.867v14.73z"></path>
                      </svg>
                  </span>
                    <span class="rrssb-text">{l s='linkedin'  mod='mpm_blog'}</span>
                  </a>
                </li>
              {/if}

              {if isset($settings['button_email']) && $settings['button_email']}
                <li class="rrssb-email" data-initwidth="6.25" data-size="37" style="width: calc(7.69231% - 9.69231px);">
                  <a href="">
                  <span class="rrssb-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M21.386 2.614H2.614A2.345 2.345 0 0 0 .279 4.961l-.01 14.078a2.353 2.353 0 0 0 2.346 2.347h18.771a2.354 2.354 0 0 0 2.347-2.347V4.961a2.356 2.356 0 0 0-2.347-2.347zm0 4.694L12 13.174 2.614 7.308V4.961L12 10.827l9.386-5.866v2.347z"></path>
                      </svg>
                  </span>
                    <span class="rrssb-text">{l s='email'  mod='mpm_blog'}</span>
                  </a>
                </li>
              {/if}

              {if isset($settings['button_pinterest']) && $settings['button_pinterest']}
                <li class="rrssb-pinterest" data-initwidth="6.25" data-size="67" style="width: calc(7.69231% - 9.69231px);">
                  <a href="">
                  <span class="rrssb-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28">
                        <path d="M14.02 1.57c-7.06 0-12.784 5.723-12.784 12.785S6.96 27.14 14.02 27.14c7.062 0 12.786-5.725 12.786-12.785 0-7.06-5.724-12.785-12.785-12.785zm1.24 17.085c-1.16-.09-1.648-.666-2.558-1.22-.5 2.627-1.113 5.146-2.925 6.46-.56-3.972.822-6.952 1.462-10.117-1.094-1.84.13-5.545 2.437-4.632 2.837 1.123-2.458 6.842 1.1 7.557 3.71.744 5.226-6.44 2.924-8.775-3.324-3.374-9.677-.077-8.896 4.754.19 1.178 1.408 1.538.49 3.168-2.13-.472-2.764-2.15-2.683-4.388.132-3.662 3.292-6.227 6.46-6.582 4.008-.448 7.772 1.474 8.29 5.24.58 4.254-1.815 8.864-6.1 8.532v.003z"></path>
                      </svg>
                  </span>
                    <span class="rrssb-text">{l s='pinterest'  mod='mpm_blog'}</span>
                  </a>
                </li>
              {/if}

              {if isset($settings['button_pocket']) && $settings['button_pocket']}
                <li class="rrssb-pocket" data-initwidth="6.25" data-size="50" style="width: calc(7.69231% - 9.69231px);">
                  <a href="">
                  <span class="rrssb-icon">
                      <svg width="32" height="28" viewBox="0 0 32 28" xmlns="http://www.w3.org/2000/svg">
                        <path d="M28.782.002c2.03.002 3.193 1.12 3.182 3.106-.022 3.57.17 7.16-.158 10.7-1.09 11.773-14.588 18.092-24.6 11.573C2.72 22.458.197 18.313.057 12.937c-.09-3.36-.05-6.72-.026-10.08C.04 1.113 1.212.016 3.02.008 7.347-.006 11.678.004 16.006.002c4.258 0 8.518-.004 12.776 0zM8.65 7.856c-1.262.135-1.99.57-2.357 1.476-.392.965-.115 1.81.606 2.496 2.453 2.334 4.91 4.664 7.398 6.966 1.086 1.003 2.237.99 3.314-.013 2.407-2.23 4.795-4.482 7.17-6.747 1.203-1.148 1.32-2.468.365-3.426-1.01-1.014-2.302-.933-3.558.245-1.596 1.497-3.222 2.965-4.75 4.526-.706.715-1.12.627-1.783-.034-1.597-1.596-3.25-3.138-4.93-4.644-.47-.42-1.123-.647-1.478-.844z"></path>
                      </svg>
                  </span>
                    <span class="rrssb-text">{l s='pocket'  mod='mpm_blog'}</span>
                  </a>
                </li>
              {/if}

              {if isset($settings['button_tumblr']) && $settings['button_tumblr']}
                <li class="rrssb-tumblr" data-initwidth="6.25" data-size="51" style="width: calc(7.69231% - 9.69231px);">
                  <a href="">
                  <span class="rrssb-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28">
                        <path d="M18.02 21.842c-2.03.052-2.422-1.396-2.44-2.446v-7.294h4.73V7.874H15.6V1.592h-3.714s-.167.053-.182.186c-.218 1.935-1.144 5.33-4.988 6.688v3.637h2.927v7.677c0 2.8 1.7 6.7 7.3 6.6 1.863-.03 3.934-.795 4.392-1.453l-1.22-3.54c-.52.213-1.415.413-2.115.455z"></path>
                      </svg>
                  </span>
                    <span class="rrssb-text">{l s='tumblr'  mod='mpm_blog'}</span>
                  </a>
                </li>
              {/if}

              {if isset($settings['button_reddit']) && $settings['button_reddit']}
                <li class="rrssb-reddit" data-initwidth="6.25" data-size="45" style="width: calc(7.69231% - 9.69231px);">
                  <a href="">
                  <span class="rrssb-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28">
                        <path d="M11.794 15.316c0-1.03-.835-1.895-1.866-1.895-1.03 0-1.893.866-1.893 1.896s.863 1.9 1.9 1.9c1.023-.016 1.865-.916 1.865-1.9zM18.1 13.422c-1.03 0-1.895.864-1.895 1.895 0 1 .9 1.9 1.9 1.865 1.03 0 1.87-.836 1.87-1.865-.006-1.017-.875-1.917-1.875-1.895zM17.527 19.79c-.678.68-1.826 1.007-3.514 1.007h-.03c-1.686 0-2.834-.328-3.51-1.005-.264-.265-.693-.265-.958 0-.264.265-.264.7 0 1 .943.9 2.4 1.4 4.5 1.402.005 0 0 0 0 0 .005 0 0 0 0 0 2.066 0 3.527-.46 4.47-1.402.265-.264.265-.693.002-.958-.267-.334-.688-.334-.988-.043z"></path>
                        <path d="M27.707 13.267c0-1.785-1.453-3.237-3.236-3.237-.792 0-1.517.287-2.08.76-2.04-1.294-4.647-2.068-7.44-2.218l1.484-4.69 4.062.955c.07 1.4 1.3 2.6 2.7 2.555 1.488 0 2.695-1.208 2.695-2.695C25.88 3.2 24.7 2 23.2 2c-1.06 0-1.98.616-2.42 1.508l-4.633-1.09c-.344-.082-.693.117-.803.454l-1.793 5.7C10.55 8.6 7.7 9.4 5.6 10.75c-.594-.45-1.3-.75-2.1-.72-1.785 0-3.237 1.45-3.237 3.2 0 1.1.6 2.1 1.4 2.69-.04.27-.06.55-.06.83 0 2.3 1.3 4.4 3.7 5.9 2.298 1.5 5.3 2.3 8.6 2.325 3.227 0 6.27-.825 8.57-2.325 2.387-1.56 3.7-3.66 3.7-5.917 0-.26-.016-.514-.05-.768.965-.465 1.577-1.565 1.577-2.698zm-4.52-9.912c.74 0 1.3.6 1.3 1.3 0 .738-.6 1.34-1.34 1.34s-1.343-.602-1.343-1.34c.04-.655.596-1.255 1.396-1.3zM1.646 13.3c0-1.038.845-1.882 1.883-1.882.31 0 .6.1.9.21-1.05.867-1.813 1.86-2.26 2.9-.338-.328-.57-.728-.57-1.26zm20.126 8.27c-2.082 1.357-4.863 2.105-7.83 2.105-2.968 0-5.748-.748-7.83-2.105-1.99-1.3-3.087-3-3.087-4.782 0-1.784 1.097-3.484 3.088-4.784 2.08-1.358 4.86-2.106 7.828-2.106 2.967 0 5.7.7 7.8 2.106 1.99 1.3 3.1 3 3.1 4.784C24.86 18.6 23.8 20.3 21.8 21.57zm4.014-6.97c-.432-1.084-1.19-2.095-2.244-2.977.273-.156.59-.245.928-.245 1.036 0 1.9.8 1.9 1.9-.016.522-.27 1.022-.57 1.327z"></path>
                      </svg>
                  </span>
                    <span class="rrssb-text">{l s='reddit'  mod='mpm_blog'}</span>
                  </a>
                </li>
              {/if}

              {if isset($settings['button_hackernews']) && $settings['button_hackernews']}
                <li class="rrssb-hackernews" data-initwidth="6.25" data-size="89" style="width: calc(7.69231% - 9.69231px);">
                  <a href="">
                  <span class="rrssb-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28">
                        <path fill="#FFF" d="M14 13.626l-4.508-9.19H6.588l6.165 12.208v6.92h2.51v-6.92l6.15-12.21H18.69"></path>
                      </svg>
                  </span>
                    <span class="rrssb-text">{l s='hackernews'  mod='mpm_blog'}</span>
                  </a>
                </li>
              {/if}

            </ul>
          {/if}


        </div>

        {if $related_articles}
          <div class="related_articles">
            <div class="related_articles_title">{l s='Related articles'  mod='mpm_blog'}</div>
            <ul class="related_articles_content">
              {foreach from=$related_articles key=key item=articles}
                <li>
                  <a class="item_related_articles" href="{$blogUrl|escape:'htmlall':'UTF-8'}{$articles['link_rewrite_category']|escape:'htmlall':'UTF-8'}/{$articles['link_rewrite']|escape:'htmlall':'UTF-8'}.html"><i class="material-icons">keyboard_arrow_right</i>{$articles['name']|escape:'htmlall':'UTF-8'}</a>
                </li> 
              {/foreach}
            </ul>
          </div>
        {/if}
        {if $related_products}
          <div class="related_products">
            <div class="related_products_title">
              <span class="title">{l s='Related products'  mod='mpm_blog'}</span>
              <div id="slider-arrows" data-slides="{$settings['number_related_products']|escape:'htmlall':'UTF-8'}" data-counts-slides="{count($related_products)|escape:'htmlall':'UTF-8'}">

                <div id="slider-prev">  </div>
                <div id="slider-next" > </div>
              </div>
            </div>


            <ul class="related_products_content">
              {foreach from=$related_products key=key item=products}
                <li>
                  <div class="slider_products">
                    <div class="left_column_related_pr">
                      <a href="{$products['link']|escape:'html':'UTF-8'}">
                       <img src="{$link->getImageLink($products['link_rewrite'], $products['id_image'], 'cart_default' )|escape:'htmlall':'UTF-8'}" alt="{$products['name']|escape:'html':'UTF-8'}" />
                      </a>
                    </div>
                    <div class="center_column_related_pr">
                      <a class="item_related_products" href="{$products['link']|escape:'htmlall':'UTF-8'}"><span class="name_product_blog">{$products['name']|truncate:30:'...'|escape:'htmlall':'UTF-8'}</span></a>
                      {if $settings['related_products_description']}<div class="description_product_blog">{$products['description_short']|truncate:80:'...'|escape:'htmlall':'UTF-8' nofilter}</div>{/if}
                    </div>
                  </div>
                </li>
              {/foreach}
            </ul>
          </div>
        {/if}
        {if isset($post['allow_comment_category']) && isset($post['allow_comment']) && $post['allow_comment_category'] && $post['allow_comment'] && $settings['use_comments']}
        <div class="comments_post">
          <div class="title_comments_block">{l s='Comments'  mod='mpm_blog'}</div>
        </div>
        <div class="user_comment">
          {if isset($comments) && $comments}
            {foreach from=$comments item=comment}
              <div class="one_user_comment">
                <div class="logo_user_comment">
                  <div class="user_comment_img">
                    <img src="{$url_base|escape:'htmlall':'UTF-8'}modules/mpm_blog/views/img/male_noavatar_user_page.jpg">
                  </div>
                  <div class="user_comment_name">{$comment['author_name']|escape:'htmlall':'UTF-8'}</div>
                  <div class="user_comment_date">{$comment['date']|escape:'htmlall':'UTF-8'}</div>
                </div>
                <div class="user_comment_content">
                  <div id="user_comment_content"><div class="comment_left_line"></div>
                    {$comment['content']|escape:'htmlall':'UTF-8'}
                  </div>
                </div>
                <div style="clear: both"></div>
              </div>
            {/foreach}
          {/if}
        </div>

        {if isset($post['allow_comment_category']) && isset($post['allow_comment']) && $post['allow_comment_category'] && $post['allow_comment'] && !$unregistered}
          <div class="block_add_new_comments">
            <div class="title_add_new_comments">{l s='Leave a comment'  mod='mpm_blog'}</div>

            <div class="add_new_comemnt_form">
              <div class=""></div>
              <div class="alert-success-blog ">
                <span>{l s='Comment successfully added!'  mod='mpm_blog'}</span>
                <div style="clear: both"></div>
              </div>
              <div class="alert-danger-blog reg">
                <span>{l s='You must be logged to enter comment!'  mod='mpm_blog'}</span>
                <div style="clear: both"></div>
              </div>
              <div class="progres_bar_ex"><div class="loading"><div></div></div></div>



              <div class="one_row_form">
                <label class="form-control-label col-md-3">{l s='Name:'  mod='mpm_blog'}</label>
                <div class="col-md-6">
                    <div class="alert-danger-blog message">
                        <span>{l s='Some error occurred please contact us!'  mod='mpm_blog'}</span>
                        <div style="clear: both"></div>
                    </div>
                    <div class="alert-danger-blog error-name">
                        <span>{l s='Enter your name!'  mod='mpm_blog'}</span>
                        <div style="clear: both"></div>
                    </div>
                  <input class="name_user_comments form-control" name="name_user_comments">
                </div>
              </div>
              <div class="one_row_form">
                <label class="form-control-label col-md-3">{l s='Email:'  mod='mpm_blog'}</label>
                <div class="col-md-6">
                    <div class="alert-danger-blog error-email">
                        <span>{l s='Enter your E-mail!'  mod='mpm_blog'}</span>
                        <div style="clear: both"></div>
                    </div>
                  <input class="email_user_comments form-control" name="email_user_comments">
                </div>
              </div>
              <div class="one_row_form">
                <label class="form-control-label col-md-3">{l s='Comment:'  mod='mpm_blog'}</label>
                <div class="col-md-6">
                    <div class="alert-danger-blog error-comment">
                        <span>{l s='Enter your comment!'  mod='mpm_blog'}</span>
                        <div style="clear: both"></div>
                    </div>
                  <textarea class="comments_text form-control" name="comments_text"></textarea>
                </div>
              </div>
              <div class="one_row_form">
                <label class="form-control-label col-md-3 form-control-label-rating">{l s='Rating:'  mod='mpm_blog'}</label>
                <div class="rate_user col-md-6"></div>
              </div>
              {if isset($settings['using_captcha']) && $settings['using_captcha']}
                <div class="one_row_form">
                  <label class="form-control-label col-md-3">{l s='Enter your comment:'  mod='mpm_blog'}</label>
                  <div class="col-md-6">
                      <div class="alert-danger-blog error-captcha">
                          <span>{l s='Enter captcha!'  mod='mpm_blog'}</span>
                          <div style="clear: both"></div>
                      </div>
                    <input type="text" class="captcha_comments form-control" name="captcha_comments">
                    <div class='captch_img_block_contact'><img src="{$captcha_url|escape:'htmlall':'UTF-8'}"></div>
                  </div>
                  <div style="clear: both"></div>
                </div>
              {/if}
              <div class="one_row_form one_row_form_button">
                <button class="btn btn-primary save_new_comment" onclick="addNewComment({$shopId|escape:'htmlall':'UTF-8'},{$langId|escape:'htmlall':'UTF-8'},{$post['id_blog_post']|escape:'htmlall':'UTF-8'})"><span>{l s='Add comment'  mod='mpm_blog'}</span></button>
              </div>
              <div style="clear: both"></div>
            </div>
          </div>
        {else}
          {if $unregistered}
            <div class="banned_comments">{l s='You must be logged to enter comment!'  mod='mpm_blog'}</div>
          {/if}
        {/if}
      {/if}

      </div>
    {/if}
  <div style="clear: both"></div>
  </div>
{/block}
