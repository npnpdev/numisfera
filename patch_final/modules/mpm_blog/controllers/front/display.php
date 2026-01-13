<?php

class mpm_blogDisplayModuleFrontController extends ModuleFrontControllerCore
{
  private $_model;
  private $_objCategory;
  private $_objPost;
  private $_objBlog;
  private $_shopId;
  private $_langId;
  private $_imgDir;

  public function __construct()
  {
    $this->php_self = 'module-mpm_blog-display';
	Context::getContext()->shop->theme->setPageLayouts(array("module-mpm_blog-display" => "layout-left-column"));
    $this->_imgDir = _PS_IMG_DIR_ . 'blog/articles/';
    parent::__construct();
  }

  public function init()
  {
    parent::init();
  }

  public function canonicalRedirection($canonical_url = '')
  {
    return false;
  }

  public function setMedia()
  {
    parent::setMedia();
    $this->registerStylesheet('rrssb', 'modules/mpm_blog/views/css/rrssb.css', array('media' => 'all', 'priority' => 150));
    $this->registerStylesheet('blog_raty', 'modules/mpm_blog/views/css/jquery.raty.css', array('media' => 'all', 'priority' => 150));
    $this->registerJavascript('blog_cookie_js', 'modules/mpm_blog/views/js/jquery.cookie.js', array('media' => 'all', 'position' => 'bottom', 'priority' => 150));
    $this->registerJavascript('blog_raty_js', 'modules/mpm_blog/views/js/jquery.raty.js', array('media' => 'all', 'position' => 'bottom', 'priority' => 150));
    $this->registerJavascript('rrssb_js', 'modules/mpm_blog/views/js/rrssb.js', array('media' => 'all', 'position' => 'bottom', 'priority' => 150));
  }

  public function getTemplateVarPage()
  {
    $def = $this->_objBlog->l('Blog', 'display');
    $page = parent::getTemplateVarPage();
    $index = $this->getSettingsIndex($this->_langId);

    if(isset($index[0]['meta_title']) && $index[0]['meta_title']){
      $title = $index[0]['meta_title'];
    }
    else{
      $title = $def;
    }

    if(isset($index[0]['meta_description']) && $index[0]['meta_description']){
      $description = $index[0]['meta_description'];
    }
    else{
      $description = $def;
    }

    if(isset($index[0]['meta_keywords']) && $index[0]['meta_keywords']){
      $keywords = $index[0]['meta_keywords'];
    }
    else{
      $keywords = $def;
    }


    $category = Tools::getValue("category");
    $article = Tools::getValue("article");
    $search = trim(Tools::getValue("search"));
    $archives = Tools::getValue("archive");

    if( $category && !$article){
      $cat = $this->_objCategory->getCategoryByLink($category, $this->_langId, $this->_shopId);
      $description = $cat['meta_description'];
      $keywords = $cat['meta_keywords'];
      if($cat['meta_title']){
        $title = $cat['meta_title'];
      }
      else{
        $title = strip_tags($cat['name']);
      }
    }
    elseif($category && $article){
      $post = $this->_objPost->getPostByLinkRewrite($category, $article, $this->_langId, $this->_shopId);


      $description = $post[0]['meta_description'];
      $keywords = $post[0]['meta_keywords'];
      if($post[0]['meta_title']){
        $title = $post[0]['meta_title'];
      }
      else{
        $title = strip_tags($post[0]['name']);
      }
    }
    elseif($archives){
      $title = $this->_objBlog->l('Blog archive: ', 'display').$archives;
      $keywords = $this->_objBlog->l('Blog archive: ', 'display').$archives;
      $description = $this->_objBlog->l('Blog archive: ', 'display').$archives;
    }
    elseif($search){
      $title = $this->_objBlog->l('Search: ', 'display').$search;
      $description = $this->_objBlog->l('Search: ', 'display').$search;
      $keywords = $this->_objBlog->l('Search: ', 'display').$search;
    }

    $page['meta'] = array(
      'title' => $title,
      'description' => $description,
      'keywords' => $keywords,
      'robots' => 'index',
    );

    return $page;
  }

  public function getBreadcrumbLinks()
  {
    $breadcrumb = parent::getBreadcrumbLinks();

    $search_br = false;
    $archives_br = false;
    $cat_br = false;
    $prod_br = false;

    $link = new Link();
    $baseUrl = $link->getPageLink('display-faq-home', true);

    $category = Tools::getValue("category");
    $article = Tools::getValue("article");
    $search = trim(Tools::getValue("search"));
    $archives = Tools::getValue("archive");


    $home =  array(
      'title' => $this->_objBlog->l('Blog', 'display'),
      'url' => $baseUrl,
    );




    if($category){
      $cat = $this->_objCategory->getCategoryByLink($category, $this->_langId, $this->_shopId);
      $cat_br =  array(
        'title' => $cat['name'],
        'url' => $baseUrl.$cat['link_rewrite'],
      );

      if($article) {
        $post = $this->_objPost->getPostByLinkRewrite($category, $article, $this->_langId, $this->_shopId);
        $name = $this->_objPost->truncate($post[0]['name'], 60);
        $prod_br =  array(
          'title' => $name,
          'url' => '',
        );
      }

    }

    if($search){
      $search_br =  array(
        'title' => $this->_objBlog->l('Search', 'display').' "'.$search.'"',
        'url' => '',
      );
    }

    if($archives){
      $archives_br =  array(
        'title' => $this->_objBlog->l('Blog archive: ', 'display').$archives,
        'url' => '',
      );
    }

    $breadcrumb['links'][] = $home;


    if($cat_br){
      $breadcrumb['links'][] = $cat_br;
    }

    if($prod_br){
      $breadcrumb['links'][] = $prod_br;
    }


    if($search_br){
      $breadcrumb['links'][] = $search_br;
    }

    if($archives_br){
      $breadcrumb['links'][] = $archives_br;
    }

    return $breadcrumb;
  }


  public function initContent()
  {
    include_once(_PS_MODULE_DIR_.'mpm_blog/datamodel.php');
    require_once(_PS_MODULE_DIR_.'mpm_blog/mpm_blog.php');
    require_once (_PS_MODULE_DIR_.'mpm_blog/classes/blogPost.php');
    require_once (_PS_MODULE_DIR_.'mpm_blog/classes/blogCategory.php');
    require_once (_PS_MODULE_DIR_.'mpm_blog/classes/blogComments.php');

    $this->_model = new blogDataModel();
    $this->_objCategory = new blogCategory();
    $this->_objPost = new blogPost();
    $this->_objComment = new blogComments();
    $this->_objBlog = new mpm_blog();
    $this->_shopId = Context::getContext()->shop->id;
    $this->_langId = Context::getContext()->language->id;

    $settings = Tools::unserialize( Configuration::get( 'GOMAKOIL_FUNCTIONAL_BLOG') );

    $post = false;
    $related_articles = false;
    $related_products = false;
    $url = false;
    $email = false;
    $index_page_block = false;
    $category_page_block = false;

    parent::initContent();

    $blogUrl  = _PS_BASE_URL_SSL_.__PS_BASE_URI__;

    if( Language::isMultiLanguageActivated() ){
      $blogUrl = $blogUrl . Context::getContext()->language->iso_code . '/blog/';
    }
    else{
      $blogUrl = $blogUrl.'blog/';
    }

    $category = Tools::getValue("category");
    $article = Tools::getValue("article");
    $search = trim(Tools::getValue("search"));
    $archives = Tools::getValue("archive");

    $n = $settings['count_post'];
    if( !Tools::getValue('p') ){
      $p = 1;
    }
    else{
      $p = Tools::getValue('p');
    }
    $limit = ($p-1)*(int)$n.','.$n;

    $path = '<span itemprop="title">'.$this->_objBlog->l('Blog', 'display').'</span>';

    if( $category && !$article){
      $posts = $this->_objPost->getPostsByLinkRewrite($category, true, $this->_langId, $this->_shopId, $limit);
      $count_posts =$this->_objPost->getCountPostsByLinkRewrite($category, $this->_langId, $this->_shopId);
      $cat = $this->_objCategory->getCategoryByLink($category, $this->_langId, $this->_shopId);
      $category_page_block = array();
      $category_page_block['image'] = false;

      if( file_exists( _PS_IMG_DIR_ . 'blog/category/' . date('Y-m',strtotime($cat['date_add']) ) . '/' . $cat['id_blog_category'] .'.jpg' ) ){
        $category_page_block['image'] = _PS_BASE_URL_SSL_.__PS_BASE_URI__ . 'img/blog/category/' . date('Y-m',strtotime($cat['date_add'])) . '/'. $cat['id_blog_category'] .'.jpg' ;
      }

      if(isset($cat['description']) && $cat['description']){
        $category_page_block['description'] =  $cat['description'];
      }

      $path_pagination = $blogUrl.$cat['link_rewrite'].'/p/';
    }
    elseif($category && $article){
      $post = $this->_objPost->getPostByLinkRewrite($category, $article, $this->_langId, $this->_shopId);
      $related_articles = $this->_objPost->getRelatedArticles($category, $article, $this->_langId, $this->_shopId);
      $related_products = $this->_objPost->getRelatedProductsById($category, $article, $this->_langId, $this->_shopId);
      $products = Product::getProductsProperties( $this->_langId, $related_products );
      if( $products ){
        $related_products = $products;
      }
      else{
        $related_products = false;
      }
      if($post){
        $email = $this->context->cookie->email;
        $url = $blogUrl.$post[0]['link_rewrite_category'].'/'.$post[0]['link_rewrite'].'.html';
      }
      $count_posts = false;
      $path_pagination = false;
      $posts = false;
    }
    elseif($archives){
      $posts = $this->_objPost->getArchive($this->_langId, $this->_shopId, $archives, $limit);
      $count_posts = $this->_objPost->getCountArchive($this->_langId, $this->_shopId, $archives);
      $path_pagination = $blogUrl.'archive/'.$archives.'/p/';
    }
    elseif($search){
      $posts = $this->_objPost->searchPost($this->_langId, $this->_shopId, $search, $limit);
      $count_posts = $this->_objPost->countSearchPost($this->_langId, $this->_shopId, $search);
      $path_pagination = $blogUrl.'search/'.$search.'/p/';
    }
    else{
      $images = false;
      $description = false;
      $set = $this->getSettingsIndex($this->_langId);

      if(isset($set[0]['description']) && $set[0]['description']){
        $description = $set[0]['description'];
      }

      $img = Tools::unSerialize(Configuration::get('GOMAKOIL_FUNCTIONAL_BLOG_IMG'));
      $path_img =  _PS_MODULE_DIR_.'mpm_blog/views/img/index.'.$img;

      if (file_exists($path_img)){
        $images = _PS_BASE_URL_SSL_.__PS_BASE_URI__.'modules/mpm_blog/views/img/index.'.$img;
      }

      $index_page_block = array(
        'active'         => $settings['active_index'],
        'description'    => $description,
        'images'         => $images,
      );
      $posts = $this->_objPost->getPostsByLinkRewrite(false, true, $this->_langId, $this->_shopId, $limit);
      $count_posts =$this->_objPost->getCountPostsByLinkRewrite(false, $this->_langId, $this->_shopId);
      $path_pagination = $blogUrl.'p/';

    }

    $tags = $this->_objPost->getTags($this->_langId, $this->_shopId);
    $most_tags = array();
    foreach($tags as $tag){
      $tag = explode(',', $tag['tags']);
      if($tag[0]){
        $most_tags[] = $tag[0];
      }
    }

    $productNb = (int)$count_posts;
    $pages_nb = ceil($productNb/$n);
    if( $pages_nb > 5 && $p > 3){
      $stop = 2 + $p;
    }
    elseif( $pages_nb > 5){
      $stop = 5;
    }
    else{
      $stop = $pages_nb;
    }
    if( $p == $pages_nb || $p == ($pages_nb + 1) ){
      $stop = $p;
    }
    if( ($p +1) == $pages_nb ){
      $stop = $p + 1;
    }
    $start = 1;
    if( $p >= 5 ){
      $start = $p - 2;
    }

    if(!$settings['unregistered_users'] && !Context::getContext()->customer->isLogged()){
      $unregistered = true;
    }
    else{
      $unregistered = false;
    }

    $comments = $this->_objComment->getComments($this->_shopId, $this->_langId, $article);

    if( $posts ){
      foreach( $posts as $key => $tmpPost ){
        $posts[$key]['is_image'] = false;
        if( file_exists( _PS_IMG_DIR_ . 'blog/' . date('Y-m',strtotime($tmpPost['date_add']) ) . '/' . $tmpPost['id_blog_post'] .'.jpg' ) ){
          $posts[$key]['is_image'] = _PS_BASE_URL_SSL_.__PS_BASE_URI__ . 'img/blog/' . date('Y-m',strtotime($tmpPost['date_add'])) . '/';
        }
      }
    }


    Media::addJsDefL('url_base', _PS_BASE_URL_SSL_.__PS_BASE_URI__);

    $this->context->smarty->assign(array(
      'path'              => $path,
      'most_tags'         => $most_tags,
      'blogUrl'           => $blogUrl,
      'posts'             => $posts,
      'langId'            => $this->_langId,
      'shopId'            => $this->_shopId,
      'post'              => $post[0] ?? false,
      'related_articles'  => $related_articles,
      'related_products'  => $related_products,
      'index_page_block'  => $index_page_block,
      'email'  => $email,
      'url'  => $url,
      'category_page_block'  => $category_page_block,
      'p'                 => (int)$p,
      'n'                 => (int)$n,
      'start'             => $start,
      'stop'              => $stop,
      'captcha_url'       => _PS_BASE_URL_SSL_.__PS_BASE_URI__.'modules/mpm_blog/secpic.php',
      'pages_nb'          => $pages_nb,
      'settings'          => $settings,
      'comments'          => $comments,
      'url_base'          => _PS_BASE_URL_SSL_.__PS_BASE_URI__,
      'path_pagination'   => $path_pagination,
      'unregistered'      => $unregistered,
    ));

    $this->setTemplate('module:mpm_blog/views/templates/front/display.tpl');
  }

  public function getSettingsIndex($id_lang){

    $sql = 'SELECT *
        FROM '._DB_PREFIX_.'blog_index_page_lang bc
        WHERE  bc.id_lang='.(int)$id_lang.'
       ';

    return Db::getInstance()->ExecuteS($sql);

  }
}
