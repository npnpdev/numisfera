<?php

require_once (dirname(__FILE__).'/../../classes/blogPost.php');
require_once (dirname(__FILE__).'/../../classes/blogCategory.php');

class AdminPostBlogController extends ModuleAdminController
{
  protected $available_tabs_lang = array();
  protected $position_identifier = 'id_blog_post';
  protected $tab_display;
  protected $available_tabs = array();
  protected $submitted_tabs;
  protected $id_current_category;
  private $_imgDir;

  public function __construct()
  {
    $this->className = 'blogPost';
    $this->table = 'blog_post';
    $this->bootstrap = true;
    $this->lang = true;
    $this->edit = true;
    $this->delete = true;
    $this->allow_export = true;
    parent::__construct();
    $this->multishop_context = -1;
    $this->multishop_context_group = true;
    $this->position_identifier = 'id_blog_post';
    $this->_defaultOrderBy = 'a!position';
    $this->orderBy = 'position';
    $this->id_current_category = 1;
    $this->_imgDir = _PS_IMG_DIR_ . 'blog/';

    $this->bulk_actions = array(
      'delete' => array(
        'icon' => 'icon-trash',
        'text' => $this->l('Delete selected'),
        'confirm' => $this->l('Delete selected items?')
      )
    );

    if (!Tools::getValue('id_blog_post'))
      $this->multishop_context_group = false;

    $this->fields_list = array();
    $this->fields_list['id_blog_post'] = array(
      'title' => $this->l('ID'),
      'align' => 'center',
      'width' => 20
    );

    $this->fields_list['name'] = array(
      'title' => $this->l('Title'),
      'filter_key' => 'b!name',
      'width' =>100
    );

    $this->fields_list['description_short'] = array(
      'title' => $this->l('Short description'),
      'maxlength' => 190,
      'width' =>200,
      'callback' => 'stripDescription',
      'orderby' => false
    );

    $this->fields_list['id_blog_category'] = array(
      'title' => $this->l('Category'),
      'align' => 'center',
      'width' => 100,
      'search' => false,
      'callback' => 'getNameCategory',
    );

    $this->fields_list['link_rewrite'] = array(
      'title' => $this->l('Friendly URL'),
      'align' => 'center',
      'width' => 100,
      'filter_key' => 'b!link_rewrite',
    );
    $this->fields_list['date_add'] = array(
      'title' => $this->l('Creation date'),
      'maxlength' => 190,
      'width' =>100
    );
    $this->fields_list['active'] = array(
      'title' => $this->l('Displayed'),
      'active' => 'status',
      'align' => 'center',
      'type' => 'bool',
      'width' => 70,
      'orderby' => false
    );

    if (Tools::getValue('id_category'))
    $this->fields_list['position'] = array(
      'title' => $this->l('Position'),
      'width' => 40,
      'filter_key' => 'a!position',
      'align' => 'center',
      'position' => 'position'
    );
  }

  public function init()
  {
    parent::init();

    if (Context::getContext()->shop->id)
      $this->_where = ' AND b.`id_shop` = '.(int)Context::getContext()->shop->id;
    $id_category = Tools::getValue('id_category');
    if(isset($id_category) && $id_category){
      $this->_where = ' AND a.`id_blog_category` = '.(int)$id_category .' AND b.`id_shop` = '.(int)Context::getContext()->shop->id;
    }
  }

  public function initContent()
  {
    $id_lang = $this->context->language->id;
    $id_shop = (int)Context::getContext()->shop->id;
    $objCategory = new blogCategory();
    $post = $objCategory->getCategories($id_lang,$id_shop);

    $this->tpl_list_vars['category_tree'] = $post;
    $this->tpl_list_vars['id_category'] = Tools::getValue('id_category');
    $this->tpl_list_vars['base_url'] = preg_replace('#&id_category=[0-9]*#', '', self::$currentIndex).'&token='.$this->token;

    parent::initContent();
  }

  protected function uploadImage($id, $name, $dir, $ext = false, $width = null, $height = null)
  {
    $this->_errors = array();
    if (isset($_FILES[$name]['tmp_name']) && !empty($_FILES[$name]['tmp_name']))
    {

      // Delete old image
      if (Validate::isLoadedObject($object = $this->loadObject()))
        $object->deleteImage();
      else
        return false;


      // Check image validity
      $max_size = isset($this->maxImageSize) ? $this->maxImageSize : 0;
      if ($error = ImageManager::validateUpload($_FILES[$name], Tools::getMaxUploadSize($max_size)))
        $this->_errors[] = $error;
      elseif (!($tmpName = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES[$name]['tmp_name'], $tmpName))
        return false;
      else
      {
        $_FILES[$name]['tmp_name'] = $tmpName;
        // Copy new image
        if (!ImageManager::resize($tmpName, $dir.$id.'.'.$this->imageType, (int)$width, (int)$height, ($ext ? $ext : $this->imageType)))
          $this->_errors[] = Tools::displayError('An error occurred while uploading image.');
        if (count($this->_errors))
          return false;
        if ($this->afterImageUpload())
        {
          unlink($tmpName);
          return true;
        }
        return false;
      }
    }

    return true;
  }

  public function initPageHeaderToolbar(){
    if ($this->display == 'view' || $this->display == 'edit')
    {
      $baseUrl = _PS_BASE_URL_SSL_.__PS_BASE_URI__;


      $article = new blogPost( Tools::getValue('id_blog_post'), Context::getContext()->language->id );
      $cat = new blogCategory( $article->id_blog_category, Context::getContext()->language->id );

      $blogUrl = $baseUrl.'blog/' . $cat->link_rewrite . '/' . $article->link_rewrite . '.html';


      $this->page_header_toolbar_btn['preview'] = array(
        'href' => $blogUrl,
        'desc' => $this->l('Preview', null, null, false),
        'short' => $this->l('Preview', null, null, false),
        'target' => true,
      );
    }
    parent::initPageHeaderToolbar();
  }

  protected function postImage($id)
  {
    $obj = $this->loadObject(true);
    if( !file_exists( $this->_imgDir ) ){
      mkdir($this->_imgDir, 0755);
    }
    if( !file_exists( $this->_imgDir.date('Y-m',strtotime($obj->date_add)) ) ){
      mkdir($this->_imgDir.date('Y-m',strtotime($obj->date_add)), 0755);
    }
    $ret = $this->uploadImage($id, 'image', $this->_imgDir.date('Y-m',strtotime($obj->date_add)).'/');
    $generate_hight_dpi_images = (bool)Configuration::get('PS_HIGHT_DPI');

    if (($id_blog_post = (int)Tools::getValue('id_blog_post')) &&
      isset($_FILES) && count($_FILES) && $_FILES['image']['name'] != null &&
      file_exists($this->_imgDir.date('Y-m',strtotime($obj->date_add)).'/'.$id_blog_post.'.jpg'))
    {
      $config = Tools::unserialize( Configuration::get( 'GOMAKOIL_FUNCTIONAL_BLOG') );


      $images_types = array(
        array(
          'name'   => 'image_list',
          'height' =>  $config['image_list_height'],
          'width' =>  $config['image_list_width'],
        ),
        array(
          'name'   => 'image_grid',
          'height' =>  $config['image_grid_height'],
          'width' =>  $config['image_grid_width'],
        ),
        array(
          'name'   => 'image_home',
          'height' =>  $config['image_home_height'],
          'width' =>  $config['image_home_width'],
        ),
        array(
          'name'   => 'image_featured',
          'height' =>  $config['image_featured_height'],
          'width' =>  $config['image_featured_width'],
        ),
      );

      foreach ($images_types as $image_type)
      {
        ImageManager::resize(
          $this->_imgDir.date('Y-m',strtotime($obj->date_add)).'/'.$id_blog_post.'.jpg',
          $this->_imgDir.date('Y-m',strtotime($obj->date_add)).'/'.$id_blog_post.'-'.Tools::stripslashes($image_type['name']).'.jpg',
          (int)$image_type['width'], (int)$image_type['height']
        );

        if ($generate_hight_dpi_images)
          ImageManager::resize(
            $this->_imgDir.date('Y-m',strtotime($obj->date_add)).'/'.$id_blog_post.'.jpg',
            $this->_imgDir.date('Y-m',strtotime($obj->date_add)).'/'.$id_blog_post.'-'.Tools::stripslashes($image_type['name']).'2x.jpg',
            (int)$image_type['width']*2, (int)$image_type['height']*2
          );
      }
    }

    return $ret;
  }

  public function postProcess()
  {
    if( Tools::getValue('deleteImage') ){
      if (Validate::isLoadedObject($this->loadObject())){
        $this->_deleteImage();
      }
    }

    if (!$this->redirect_after)
      parent::postProcess();
  }

  private function _deleteImage()
  {

    $id_blog_post = (int)Tools::getValue('id_blog_post');
    $images_types = array(
      array(
        'name'   => 'image_list',
      ),
      array(
        'name'   => 'image_grid',
      ),
      array(
        'name'   => 'image_featured',
      ),
    );
    $obj = $this->loadObject(true);
    foreach ($images_types as $image_type) {
      if (file_exists($this->_imgDir.date('Y-m',strtotime($obj->date_add)).'/'.$id_blog_post.'-'.Tools::stripslashes($image_type['name']).'.jpg')) {
        unlink($this->_imgDir.date('Y-m',strtotime($obj->date_add)).'/'.$id_blog_post.'-'.Tools::stripslashes($image_type['name']).'.jpg');
      }
      if (file_exists($this->_imgDir.date('Y-m',strtotime($obj->date_add)).'/'.$id_blog_post.'.jpg')) {
        unlink($this->_imgDir.date('Y-m',strtotime($obj->date_add)).'/'.$id_blog_post.'.jpg');
      }
    }
  }

  public static function getNameCategory($id_category)
  {
    $cat = new blogCategory($id_category);
    return $cat->name[Context::getContext()->shop->id];
  }

  public function ajaxProcessUpdatePositions()
  {
    $blog_posts = Tools::getValue('blog_post');
    foreach($blog_posts as $key => $value){
      $value = explode('_', $value);
      Db::getInstance()->update('blog_post', array('position' => (int)$key+1), 'id_blog_post='.(int)$value[2]);
    }
  }

  public function renderForm()
  {
    Configuration::updateValue('GOMAKOIL_PRODUCTS_CHECKED', '');
    Configuration::updateValue('GOMAKOIL_POSTS_CHECKED', '');

    $obj = $this->loadObject(true);
    $image = $this->_imgDir.date('Y-m',strtotime($obj->date_add)).'/'.$obj->id.'.jpg';
    $image_url = ImageManager::thumbnail($image, $this->table.'_'.(int)$obj->id.'.'.$this->imageType, 350,$this->imageType, true, true);
    $image_size = file_exists($image) ? filesize($image) / 1000 : false;
    $id_lang = $this->context->language->id;
    $id = (int)Context::getContext()->shop->id;
    $id_shop = $id ? $id: Configuration::get('PS_SHOP_DEFAULT');
    $objCate = new blogCategory();
    $categories = $objCate->getCategories($id_lang,$id_shop);
    $objPost = new blogPost();
    $id_blog_post = Tools::getValue('id_blog_post');
    $post = $objPost->getPost($id_lang,$id_shop,false,$id_blog_post);
    $obj = new blogPost(Tools::getValue('id_blog_post'));
    $products = Product::getProducts($id_lang, 0, 300, 'name', 'asc' );
    $config = Tools::unserialize( Configuration::get( 'GOMAKOIL_FUNCTIONAL_BLOG') );

    if(isset($id_blog_post) && $id_blog_post ){
      $related_products = $objPost->getRelatedProducts($id_lang, $id_shop, $id_blog_post);
      $related_products = Tools::unserialize($related_products);
    }
    else{
      $related_products = array();
    }
    if(isset($id_blog_post) && $id_blog_post ){
      $related_posts = $objPost->getRelatedPosts($id_lang, $id_shop, $id_blog_post);
      $related_posts = Tools::unserialize($related_posts);
    }
    else{
      $related_posts = array();
    }
    $this->fields_form = array(
      'tinymce' => true,
      'legend' => array(
        'title' => $this->l('Article'),
        'icon' => 'icon-tags'
      ),
      'input' => array(
        array(
          'type' => 'text',
          'label' => $this->l('Title'),
          'name' => 'name',
          'lang' => true,
          'size' => 48,
          'required' => true,
          'class' => 'copy2friendlyUrl',
          'hint' => $this->l('Invalid characters:').' <>;=#{}',
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Enabled'),
          'name' => 'active',
          'required' => false,
          'values' => array(
            array(
              'id' => 'active_on',
              'value' => 1,
              'label' => $this->l('Enabled')
            ),
            array(
              'id' => 'active_off',
              'value' => 0,
              'label' => $this->l('Disabled')
            )
          )
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Allow comment'),
          'name' => 'allow_comment',
          'required' => false,
          'form_group_class' => $config['use_comments'] ? '' : 'disabled_comments',
          'values' => array(
            array(
              'id' => 'allow_comment_on',
              'value' => 1,
              'label' => $this->l('Enabled')
            ),
            array(
              'id' => 'allow_comment_off',
              'value' => 0,
              'label' => $this->l('Disabled')
            )
          )
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Featured'),
          'name' => 'show_in_most',
          'required' => false,
          'values' => array(
            array(
              'id' => 'allow_comment_on',
              'value' => 1,
              'label' => $this->l('Enabled')
            ),
            array(
              'id' => 'allow_comment_off',
              'value' => 0,
              'label' => $this->l('Disabled')
            )
          )
        ),
        array(
          'type' => 'file',
          'label' => $this->l('Cover Image'),
          'name' => 'image',
          'display_image' => true,
          'image' => $image_url ? $image_url : false,
          'size' => $image_size,
          'delete_url' => self::$currentIndex.'&'.$this->identifier.'='.$obj->id.'&token='.$this->token.'&deleteImage=1',
        ),
        array(
          'type' => 'textarea',
          'label' => $this->l('Short description'),
          'name' => 'description_short',
          'lang' => true,
          'autoload_rte' => true,
          'rows' => 10,
          'cols' => 100,
          'hint' => $this->l('Invalid characters:').' <>;=#{}'
        ),
        array(
          'type' => 'textarea',
          'label' => $this->l('Description'),
          'name' => 'description',
          'autoload_rte' => true,
          'lang' => true,
          'rows' => 10,
          'cols' => 100,
          'hint' => $this->l('Invalid characters:').' <>;=#{}'
        ),
        array(
          'type' => 'select',
          'label' => $this->l('Category'),
          'name' => 'categoryBox',
          'class' => 'chosen',
          'default_value' => (int)$obj->id_blog_category,
          'options' => array(
            'query' =>$categories,
            'id' => 'id_blog_category',
            'name' => 'name',
            'class' => 'name',
            'value' => 'id_blog_category'
          )
        ),
        array(
          'type' => 'checkbox_table',
          'name' => 'posts[]',
          'class_block' => 'post_list',
          'label' => $this->l('Related Articles'),
          'class_input' => 'select_posts',
          'lang' => true,
          'search' => true,
          'display'=> true,
          'id_shop' => $id_shop,
          'id_lang' => $id_lang,
          'hint' => '',
          'values' => array(
            'query' => $post,
            'id' => 'id_blog_post',
            'name' => 'name',
            'value' => $related_posts
          )
        ),
        array(
          'type' => 'checkbox_table',
          'name' => 'products[]',
          'class_block' => 'product_list',
          'label' => $this->l('Related Products'),
          'class_input' => 'select_products',
          'lang' => true,
          'search' => true,
          'hint' => '',
          'display'=> true,
          'id_shop' => $id_shop,
          'id_lang' => $id_lang,
          'values' => array(
            'query' => $products,
            'id' => 'id_product',
            'name' => 'name',
            'value' => $related_products
          )
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Meta title:'),
          'name' => 'meta_title',
          'lang' => true,
          'size' => 48,
          'hint' => $this->l('Forbidden characters:').' <>;=#{}'
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Meta description'),
          'name' => 'meta_description',
          'lang' => true,
          'size' => 48,
          'hint' => $this->l('Forbidden characters:').' <>;=#{}'
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Meta keywords'),
          'name' => 'meta_keywords',
          'lang' => true,
          'size' => 48,
          'hint' => $this->l('Forbidden characters:').' <>;=#{}',
        ),
        array(
          'type' => 'tags',
          'label' => $this->l('Tags'),
          'name' => 'tags',
          'lang' => true,
          'size' => 48
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Friendly URL'),
          'name' => 'link_rewrite',
          'lang' => true,
          'size' => 48,
          'required' => true,
          'hint' => $this->l('Only letters and the minus (-) character are allowed.'),
        ),
        array(
          'type' => 'hidden',
          'name' => 'id_shop',
        ),
        array(
          'type' => 'hidden',
          'name' => 'id_lang',
        ),
        array(
          'type' => 'hidden',
          'name' => 'PS_ALLOW_ACCENTED_CHARS_URL',
        ),
      ),
      'submit' => array(
        'title' => $this->l('Save')
      ),
      'buttons' => array(
        'save-and-stay' => array(
          'title' => $this->l('Save and stay'),
          'name' => 'submitAdd'.$this->table.'AndStay',
          'type' => 'submit',
          'class' => 'btn btn-default pull-right',
          'icon' => 'process-icon-save'
        ),
      ),
    );

    $this->fields_value['id_shop'] = $id_shop;
    $this->fields_value['id_lang'] = $id_lang;
    $this->fields_value['PS_ALLOW_ACCENTED_CHARS_URL'] = Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL',null,null,$id_shop);

    return parent::renderForm();
  }

  public function renderList()
  {
    $this->addRowAction('edit');
    $this->addRowAction('delete');
    return parent::renderList();
  }

  public function processAdd()
  {
    if(!Tools::getValue('categoryBox'))
      $this->errors[] = Tools::displayError('Category empty.');
    parent::processAdd();
  }

  public static function stripDescription($description)
  {
    return mb_substr(strip_tags(Tools::stripslashes($description)),0,130);
  }

}
