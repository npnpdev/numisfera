<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.08.15
 * Time: 17:40
 */
require_once(dirname(__FILE__) . '/../../classes/topMenuGroup.php');
require_once(dirname(__FILE__) . '/../../classes/topMenuLink.php');


class AdminTopMenuGroupController extends ModuleAdminController
{
  private $_idShop;
  private $_idLang;
  private $_imgDir;

  public function __construct()
  {

    parent::__construct();

    $this->_idShop = Context::getContext()->shop->id;
    $this->_idLang = Context::getContext()->language->id;
    $this->_imgDir = _PS_MODULE_DIR_ . 'mpm_topmenu/views/img/';
  }

public function fixJSON($json) {
    $regex = <<<'REGEX'
~
    "[^"\\]*(?:\\.|[^"\\]*)*"
    (*SKIP)(*F)
  | '([^'\\]*(?:\\.|[^'\\]*)*)'
~x
REGEX;

    return preg_replace_callback($regex, function($matches) {
      return '"' . preg_replace('~\\\\.(*SKIP)(*F)|"~', '\\"', $matches[1]) . '"';
    }, $json);
  }


  public function saveGroup(){
    $id_topmenu = Tools::getValue('id_topmenu');
    $id_topmenu_column = Tools::getValue('id_topmenu_column');
    $id_group = Tools::getValue('id_group');
    $title_admin = Tools::getValue('title_admin');
    $type = Tools::getValue('type');
    $active = Tools::getValue('active');
    $position = Tools::getValue('position');
    $text_color = Tools::getValue('text_color');
    $text_color_hover = Tools::getValue('text_color_hover');
    $background_color = Tools::getValue('background_color');
    $description_after = Tools::getValue('description_after');
    $description_before = Tools::getValue('description_before');
    $description = Tools::getValue('description');
    $title = Tools::getValue('title');
    $ident = (int)Tools::getValue('ident');
    $product = Tools::getValue('product');
    $category = Tools::getValue('category');
    $link = Tools::getValue('link');
    $cms = Tools::getValue('cms');
    $brand = Tools::getValue('brand');
    $supplier = Tools::getValue('supplier');
    $page = Tools::getValue('page');

    $type_img = Tools::getValue('type_img');
    $product_title = (int)Tools::getValue('product_title');
    $product_img = (int)Tools::getValue('product_img');
    $product_price = (int)Tools::getValue('product_price');
    $product_add = (int)Tools::getValue('product_add');
    $subcategories = (int)Tools::getValue('subcategories');

    $title = json_decode($title , true);

    $description = json_decode($description , true);
    $description_before = json_decode($description_before, true);
    $description_after = json_decode($description_after , true);

    foreach ($description_before as $key => $value){
      $description_before[$key] = rawurldecode($value);
    }
    foreach ($description_after as $key => $value){
      $description_after[$key] = rawurldecode($value);
    }
    foreach ($description as $key => $value){
      $description[$key] = rawurldecode($value);
    }

    if($id_group){
      $obj = new topMenuGroup($id_group);
    }
    else{
      $obj = new topMenuGroup();
    }

    $obj->id_topmenu_column = $id_topmenu_column;
    $obj->id_topmenu = $id_topmenu;
    $obj->active = $active;
    $obj->date_add =  date('Y-m-d H:i:s');
    $obj->ident =  $ident;
    $obj->position =  $position;
    $obj->title =  $title_admin;
    $obj->background_color =  $background_color;
    $obj->text_color =  $text_color;
    $obj->text_color_hover =  $text_color_hover;
    $obj->type =  $type;
    $obj->categories =  $category;
    $obj->products =  $product;
    $obj->cms =  $cms;
    $obj->link =  $link;
    $obj->brands =  $brand;
    $obj->suppliers =  $supplier;
    $obj->pages =  $page;
    $obj->title_front =  $title;
    $obj->description =  $description;
    $obj->description_before =  $description_before;
    $obj->description_after =  $description_after;
    $obj->type_img =  $type_img;
    $obj->product_title =  $product_title;
    $obj->product_img =  $product_img;
    $obj->product_price =  $product_price;
    $obj->product_add =  $product_add;
    $obj->subcategories =  $subcategories;

    $obj->images = 0;
    $obj->save();
    if (isset($_FILES['file']) AND !empty($_FILES['file']['tmp_name']))
    {
      $img = $this->uploadImages($obj->id);
      if($img){
        $obj->images = 1;
        $obj->save();
      }
    }

    return $obj->id;
  }


  protected function uploadImages($id)
  {
      $max_size = isset($this->maxImageSize) ? $this->maxImageSize : 0;
      if (ImageManager::validateUpload($_FILES['file'], Tools::getMaxUploadSize($max_size))){
      }
      elseif (!($tmpName = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES['file']['tmp_name'], $tmpName)){
        return false;
      }
      else
      {
        $_FILES['file']['tmp_name'] = $tmpName;

        if (!ImageManager::resize($tmpName, $this->_imgDir.$id.'.png')){
          return false;
        }
        unlink($tmpName);
      }

    return true;
  }



  public function getFormColumn($ident, $id_topmenu_column){
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'mpm_topmenu/views/templates/hook/group.tpl');
    $languages = Language::getLanguages(false);
    $type_img = ImageType::getImagesTypes('products');

    $data->assign(
      array(
        'id_topmenu_column'   => $id_topmenu_column,
        'ident'               => $ident,
        'type_img'               => $type_img,
        'languages'           => $languages,
        'defaultFormLanguage' => $this->default_form_language,
      )
    );
    return $data->fetch();
  }


  public function displayAjax()
  {
    $json = array();
    try{


      if (Tools::getValue('action') == 'removeGroup') {
        $id_group = Tools::getValue('id_group');

        if($id_group){
          $obj = new topMenuGroup($id_group);
          $obj->delete();
        }
        $json['success'] = Module::getInstanceByName('mpm_topmenu')->l("Successfully saved!") ;
      }

      if (Tools::getValue('action') == 'updatePosition') {
        $position = Tools::getValue('position');
        foreach ($position as $key => $value){
          $obj = new topMenuGroup($key);
          $obj->position = $value;
          $obj->ident = $value;
          $obj->save();
        }
        $json['success'] = Module::getInstanceByName('mpm_topmenu')->l("Successfully saved!") ;
      }


      if (Tools::getValue('action') == 'addNewItemGroup') {
        $id = Tools::getValue('id');
        $id_topmenu_column = Tools::getValue('id_topmenu_column');
        $json['form'] = $this->getFormColumn($id, $id_topmenu_column);
      }


      if (Tools::getValue('action') == 'editLinkItem') {

        $ident = Tools::getValue('ident');
        $id_lang = Tools::getValue('id_lang');
        $id_shop = Tools::getValue('id_shop');
        $value = Tools::getValue('value');
        $id = Tools::getValue('id');
        $json['form'] = $this->getLinkBlock($ident, $id_lang, $id_shop, $value, $id);
        $json['success'] = Module::getInstanceByName('mpm_topmenu')->l("Successfully saved!");
      }


      if (Tools::getValue('action') == 'removeImage') {

        $id_group = (int)Tools::getValue('id_group');

        unlink($this->_imgDir.$id_group.'.png');
        $json['success'] = Module::getInstanceByName('mpm_topmenu')->l("Successfully saved!") ;
      }


      if (Tools::getValue('action') == 'saveGroup') {
        $id_group = $this->saveGroup();
        $json['id_group'] =  $id_group;
        $json['success'] = Module::getInstanceByName('mpm_topmenu')->l("Successfully saved!") ;
      }

      if (Tools::getValue('action') == 'getContentType') {

        $type = Tools::getValue('type');
        $id_lang = (int)Tools::getValue('id_lang');
        $id_shop = (int)Tools::getValue('id_shop');
        $ident = (int)Tools::getValue('id');


        $tpl = $this->getContentByType($id_lang, $id_shop, $type, false, $ident);

        $json['form'] = $tpl;

      }


      if (Tools::getValue('action') == 'addProduct') {
        $products = Tools::getValue('products');

        if($products){
          $products = implode(",", $products);
        }

        $list = $this->getProductList($products, Tools::getValue('id_lang'), Tools::getValue('id_shop'));

        if(!$list){
          $json['list'] = ' ';
          $json['products'] = ' ';
        }
        else{
          $json['list'] = $list;
          $json['products'] = $products;
        }

      }

      if (Tools::getValue('action') == 'addNewGroup') {
        $json['groups'] = '';
        $id_topmenu = (int)Tools::getValue('id_topmenu');
        $id_topmenu_column = (int)Tools::getValue('id_topmenu_column');
        $id_lang = (int)Tools::getValue('id_lang');
        $id_shop = (int)Tools::getValue('id_shop');
        $columns = $this->getColumns($id_lang, $id_shop, $id_topmenu);


        if($columns){
          if(!$id_topmenu_column){
            $id_topmenu_column = $columns[0]['id_topmenu_column'];
          }

          $groups = $this->getGroups($id_lang, $id_shop, $id_topmenu_column, $id_topmenu);

          if($groups){
            $tpl = $this->getGroupsForm($id_lang, $id_shop, $id_topmenu, $id_topmenu_column, $groups);
          }
          else{
            $tpl = $this->getGroupForm($id_lang, $id_shop, $id_topmenu, $id_topmenu_column, 1);
          }

          $json['columns'] = $this->getColumnsTab($id_lang, $id_shop, $columns, $id_topmenu_column) ;
          $json['groups'] =  $tpl;
        }
        else{
          $json['columns'] = '' ;
          $json['groups'] = '';
        }


      }

      die( json_encode($json) );
    }
    catch(Exception $e){
      $json['error'] = $e->getMessage();

      if( $e->getCode() == 10 ){
        $json['error_message'] = $e->getMessage();
      }
    }
    die( json_encode($json) );
  }

  public function getProductList($ids, $idLang, $idShop){

    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'mpm_topmenu/views/templates/hook/productList.tpl');


    if($ids){
      $items = $this->getProductsByIds($idLang, $idShop, $ids);

      $type_img = ImageType::getImagesTypes('products');
      foreach( $type_img as $key => $val){
        $pos = strpos($val['name'], 'cart_def');
        if($pos !== false){
          $type_i = $val['name'];
        }
      }
      foreach($items as $key => $item){
        $items[$key]['image'] = str_replace('http://', Tools::getShopProtocol(), Context::getContext()->link->getImageLink($item['link_rewrite'], $item['id_image'], $type_i));
      }
    }
    else{
      $items = false;
    }

    $data->assign(
      array(
        'id_shop' => $idShop,
        'id_lang' => $idLang,
        'items'   => $items,
      )
    );

    return $data->fetch();
  }

  public function getPagesBlock($ident, $id_lang, $id_shop, $ids_selected){

    $value = array();
    $pages = Meta::getPages();
    $selected = array();
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'mpm_topmenu/views/templates/hook/table.tpl');

    if($ids_selected){
      $selected = explode(",", $ids_selected);
    }

    foreach ($pages as $key => $val){
      $checked = false;
      if($selected && in_array($val, $selected)){
        $checked = true;
      }
      $value[] = array(
        'id'      => false,
        'value'   => $key,
        'title'   => $val,
        'name'    => 'pages[]',
        'checked' => $checked,
      );
    }

    $data->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'ident'   => $ident,
        'value'   => $value,
        'label'   => $this->l('Pages'),
        'class'   => 'getPagesBlock',
      )
    );

    return $data->fetch();
  }
  public function getSuppliersBlock($ident, $id_lang, $id_shop, $ids_selected){

    $value = array();
    $selected = array();
    $suppliers = Supplier::getSuppliers(false, $id_lang);
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'mpm_topmenu/views/templates/hook/table.tpl');

    if($ids_selected){
      $selected = explode(",", $ids_selected);
    }

    foreach ($suppliers as $val){
      $checked = false;
      if($selected && in_array($val['id_supplier'], $selected)){
        $checked = true;
      }
      $value[] = array(
        'id'      => $val['id_supplier'],
        'value'   => $val['id_supplier'],
        'title'   => $val['name'],
        'name'    => 'suppliers[]',
        'checked' => $checked,
      );
    }

    $data->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'ident'   => $ident,
        'value'   => $value,
        'label'   => $this->l('Suppliers'),
        'class'   => 'getSuppliersBlock',
      )
    );

    return $data->fetch();
  }


  public function getBrandBlock($ident, $id_lang, $id_shop, $ids_selected){

    $value = array();
    $selected = array();
    $manufacturers = Manufacturer::getManufacturers(false, $id_lang);
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'mpm_topmenu/views/templates/hook/table.tpl');

    if($ids_selected){
      $selected = explode(",", $ids_selected);
    }

    foreach ($manufacturers as $val){

      $checked = false;
      if($selected && in_array($val['id_manufacturer'], $selected)){
        $checked = true;
      }

      $value[] = array(
        'id'      => $val['id_manufacturer'],
        'value'   => $val['id_manufacturer'],
        'title'   => $val['name'],
        'name'    => 'manufacturers[]',
        'checked' => $checked,
      );
    }

    $data->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'ident'   => $ident,
        'value'   => $value,
        'label'   => $this->l('Brands'),
        'class'   => 'getBrandBlock',
      )
    );
    return $data->fetch();
  }



  public function getCmsBlock($ident, $id_lang, $id_shop, $ids_selected){

    $value = array();
    $selected = array();
    $cms_all =  CMS::getCMSPages($id_lang, 0, true, $id_shop);
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'mpm_topmenu/views/templates/hook/table.tpl');


    if($ids_selected){
      $selected = explode(",", $ids_selected);
    }

    foreach ($cms_all as $val){

      $checked = false;
      if($selected && in_array($val['id_cms'], $selected)){
        $checked = true;
      }

      $value[] = array(
        'id'   => $val['id_cms'],
        'value'   => $val['id_cms'],
        'title'    => $val['meta_title'],
        'name'    => 'cms[]',
        'checked' => $checked,
      );
    }

    $data->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'ident'   => $ident,
        'value'   => $value,
        'label'    => $this->l('Cms'),
        'class' => 'getCmsBlock',
      )
    );

    return $data->fetch();
  }


  public function getLinkBlock($ident, $id_lang, $id_shop, $value, $edit = false){

    $fields_form = array(
      'form' => array(
        'input' => array(
          array(
            'type' => 'text',
            'form_group_class'=> 'topMenuLinkTitle',
            'label' => $this->l('Title link'),
            'name' => 'titlelink',
            'lang' => true,
          ),
          array(
            'type' => 'text',
            'label' => $this->l('Url'),
            'name' => 'url',
            'form_group_class'=> 'topMenuLinkUrl',
            'lang' => true,
          ),
          array(
            'type' => 'html',
            'name' => 'html',
            'form_group_class'=> 'topMenuLinkBlock',
            'html_content' => '<button data-id="'.$ident.'" type="button" class="btn btn-default add_link"><span>'.$this->l('Add link').'</span></button>',
          ),

          array(
            'type' => 'html',
            'name' => 'html',
            'form_group_class'=> 'topMenuLinkList',
            'html_content' => $this->getLinkList($ident, $id_lang, $id_shop, $value),
          ),

          array(
            'type' => 'hidden',
            'name' => 'hidden_id_link',
          ),

          array(
            'type' => 'hidden',
            'name' => 'ids_link',
          ),

        ),
      ),
    );

    $helper = new HelperForm();
    $helper->show_toolbar = false;
    $helper->table =  $this->table;
    $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
    $helper->default_form_language = $lang->id;
    $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
    $helper->identifier = $this->identifier;
    $helper->tpl_vars = array(
      'languages' => $this->context->controller->getLanguages(),
      'id_language' => $this->context->language->id
    );

    $ids = " ";
    if($value){
      $ids = $value;
    }

    $languages = Language::getLanguages(false);
    $titlelink = array();
    $url = array();
    $hidden_id_link = 0;

    foreach ($languages as $lang){
      $titlelink[$lang['id_lang']] = "";
      $url[$lang['id_lang']] = "";
    }

    if($edit){
      $obj = new topMenuLink($edit);
      $titlelink = $obj->title;
      $url = $obj->link;
      $hidden_id_link = $obj->id_topmenu_link;
    }


    $helper->fields_value = array(
      'titlelink'      => $titlelink,
      'url'            => $url,
      'hidden_id_link' => $hidden_id_link,
      'ids_link'       => $ids,
    );
    return $helper->generateForm(array($fields_form));
  }


  public function getLinkList($ident, $id_lang, $id_shop, $value){

    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'mpm_topmenu/views/templates/hook/linkList.tpl');

    $links = false;


    if($value){
      $ids = explode(",", $value);

      foreach ($ids as $val){
        $obj = new topMenuLink($val, $id_lang, $id_shop);
        $links[] = array(
          'title' => $obj->title,
          'url' => $obj->link,
          'id' => $obj->id_topmenu_link,
        );
      }
    }



    $data->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'ident'   => $ident,
        'items'   => $links,
      )
    );

    return $data->fetch();
  }

  public function getDescriptionBlock($ident, $id_lang, $id_shop, $value){

      $fields_form = array(
        'form' => array(
          'input' => array(
            array(
              'type' => 'textarea',
              'label' => $this->l('Description'),
              'form_group_class'=> 'descriptionBox',
              'name' => 'description',
              'autoload_rte' => true,
              'lang' => true,
              'cols' => 15,
              'rows' => 3,
            ),
          ),
        ),
      );

      $helper = new HelperForm();
      $helper->show_toolbar = false;
      $helper->table =  $this->table;
      $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
      $helper->default_form_language = $lang->id;
      $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
      $helper->identifier = $this->identifier;
      $helper->tpl_vars = array(
        'languages' => $this->context->controller->getLanguages(),
        'id_language' => $this->context->language->id
      );


    $languages = Language::getLanguages(false);
    $description = array();

    foreach ($languages as $lang){
      $description[$lang['id_lang']] = " ";
    }

    if($value){
      $description = $value;
    }

    $helper->fields_value = array(
      'description' => $description,
    );
    return $helper->generateForm(array($fields_form));
  }


  public function getImageBlock($ident, $id_lang, $id_shop, $value){

    $ext = 'png';
    $delete = '';
    $image_url = false;
    $image_size = '';

    if($value){
      $image = $this->_imgDir.$value.'.'.$ext;
      $image_url = ImageManager::thumbnail($image, $this->table.'_'.(int)$value.'.'.$ext, 350, $ext, true, true);
      $image_size = file_exists($image) ? filesize($image) / 1000 : false;
      $delete = $value;
    }

      $fields_form = array(
        'form' => array(
          'input' => array(
            array(

              'type'              => 'file',
              'label'             => $this->l('Image'),
              'form_group_class'  => 'uploadImagesForm',
              'image'             => $image_url ? $image_url : false,
              'name'              => 'image',
              'size'              => $image_size,
              'delete_url'        => $delete,
            )
          ),
        ),
      );

      $helper = new HelperForm();
      $helper->show_toolbar = false;
      $helper->table =  $this->table;
      $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
      $helper->default_form_language = $lang->id;
      $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
      $helper->identifier = $this->identifier;
      $helper->tpl_vars = array(
        'languages' => $this->context->controller->getLanguages(),
        'id_language' => $this->context->language->id
      );

      return $helper->generateForm(array($fields_form));
  }


  public function getCategoryBlock($ident, $id_lang, $id_shop, $val, $subcategories){
      $subcat = 0;

      $selected_cat = array();

      if($val){
        $selected_cat = explode(",", $val);
      }

      $fields_form = array(
        'form' => array(
          'input' => array(
            array(
              'type'    => 'switch',
              'label'   => $this->l('Show subcategories'),
              'name'    => 'subcategories_'.$ident,
              'is_bool' => true,
              'values'  => array(
                array(
                  'id'    => 'active_on',
                  'value' => 1,
                  'label' => $this->l('Yes')),
                array(
                  'id'    => 'active_off',
                  'value' => 0,
                  'label' => $this->l('No')),
              ),
            ),
            array(
              'type' => 'categories',
              'name' => 'categoryBox',
              'class' => 'categoryBox',
              'form_group_class'=> 'bestCategoryBox',
              'label' =>  $this->l('Select categories'),
              'tree' => array(
                'id' => 'categories-tree-home',
                'selected_categories' => $selected_cat,
                'root_category' => 2,
                'use_search' => false,
                'use_checkbox' => true
              ),
            )
          ),
        ),
      );

      $helper = new HelperForm();
      $helper->show_toolbar = false;
      $helper->table =  $this->table;
      $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
      $helper->default_form_language = $lang->id;
      $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
      $helper->identifier = $this->identifier;
      $helper->tpl_vars = array(
        'languages' => $this->context->controller->getLanguages(),
        'id_language' => $this->context->language->id
      );


      $helper->fields_value = array(
        'subcategories_'.$ident => $subcategories,
      );

      return $helper->generateForm(array($fields_form));
  }


  public function getProductBlock($ident, $idLang, $idShop, $value,  $product_title, $product_img, $product_price, $product_add, $type_img_select){

    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'mpm_topmenu/views/templates/hook/productBlock.tpl');
    $ids = '';
    $items = array();
    $type_img_all = ImageType::getImagesTypes('products');

    if($value){
      $ids = $value;
      $items = $this->getProductsByIds($idLang, $idShop, $value);
      foreach( $type_img_all as $key => $val){
        $pos = strpos($val['name'], 'cart_def');
        if($pos !== false){
          $type_i = $val['name'];
        }
      }
      foreach($items as $key => $item){
        $items[$key]['image'] = str_replace('http://', Tools::getShopProtocol(), Context::getContext()->link->getImageLink($item['link_rewrite'], $item['id_image'], $type_i));
      }

    }

    $data->assign(
      array(
        'id_shop' => $idShop,
        'id_lang' => $idLang,
        'ident'   => $ident,
        'ids'     => $ids,
        'items'   => $items,
        'type_img_all'=> $type_img_all,
        'product_title'=> $product_title,
        'product_img'=> $product_img,
        'product_price'=> $product_price,
        'product_add'=> $product_add,
        'type_img_select'=> $type_img_select,
      )
    );

    return $data->fetch();
  }



  public function getProductsByIds($id_lang, $id_shop, $productsIds){
    $sql = '
			SELECT pl.name, p.*, i.id_image, pl.link_rewrite, p.reference
      FROM ' . _DB_PREFIX_ . 'product_lang as pl
      LEFT JOIN ' . _DB_PREFIX_ . 'image as i
      ON i.id_product = pl.id_product AND i.cover=1
      INNER JOIN ' . _DB_PREFIX_ . 'product as p
      ON p.id_product = pl.id_product
      WHERE pl.id_lang = ' . (int)$id_lang . '
      AND pl.id_shop = ' . (int)$id_shop . '
      AND p.id_product IN ('.pSQL($productsIds).')
			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function getGroupForm($id_lang, $id_shop, $id_topmenu, $id_topmenu_column, $ident){

    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'mpm_topmenu/views/templates/hook/group.tpl');
    $languages = Language::getLanguages(false);

    $type_img = ImageType::getImagesTypes('products');
    $data->assign(
      array(
        'ident'               => $ident,
        'id_topmenu_column'   => $id_topmenu_column,
        'id_lang'             => $id_lang,
        'id_shop'             => $id_shop,
        'type_img'             => $type_img,
        'languages'           => $languages,
        'defaultFormLanguage' => $this->default_form_language,
      )
    );
    return $data->fetch();

  }


  public function getContentByType($id_lang, $id_shop, $type, $group = false, $ident = false){

    $tpl = "";
    $value = false;
    $type_img = false;

    if($type == 'product'){
      $product_img = false;
      $product_title = false;
      $product_price = false;
      $product_add = false;
      $type_img = false;

      if($group){
        if(isset($group->products) && $group->products){
          $value = $group->products;
        }

        $product_title = $group->product_title;
        $product_img = $group->product_img;
        $product_price = $group->product_price;
        $product_add = $group->product_add;
        $type_img = $group->type_img;

      }



      $tpl = $this->getProductBlock($ident, $id_lang, $id_shop, $value, $product_title, $product_img, $product_price, $product_add, $type_img);
    }
    if($type == 'category'){
      $value2 = 1;
      if(isset($group->categories) && $group->categories){
        $value = $group->categories;
      }
      if(isset($group->subcategories)){
        $value2 = $group->subcategories;
      }
      $tpl = $this->getCategoryBlock($ident, $id_lang, $id_shop, $value, $value2);
    }
    if($type == 'cms'){
      if(isset($group->cms) && $group->cms){
        $value = $group->cms;
      }
      $tpl = $this->getCmsBlock($ident, $id_lang, $id_shop, $value);
    }
    if($type == 'brand'){
      if(isset($group->brands) && $group->brands){
        $value = $group->brands;
      }
      $tpl = $this->getBrandBlock($ident, $id_lang, $id_shop, $value);
    }
    if($type == 'supplier'){
      if(isset($group->suppliers) && $group->suppliers){
        $value = $group->suppliers;
      }
      $tpl = $this->getSuppliersBlock($ident, $id_lang, $id_shop, $value);
    }
    if($type == 'page'){
      if(isset($group->pages) && $group->pages){
        $value = $group->pages;
      }
      $tpl = $this->getPagesBlock($ident, $id_lang, $id_shop, $value);
    }
    if($type == 'image'){
      if(isset($group->id_topmenu_group) && $group->id_topmenu_group){
        $value = $group->id_topmenu_group;
      }
      $tpl = $this->getImageBlock($ident, $id_lang, $id_shop, $value);
    }
    if($type == 'description'){
      if(isset($group->description) && $group->description){
        $value = $group->description;
      }
      $tpl = $this->getDescriptionBlock($ident, $id_lang, $id_shop, $value);
    }
    if($type == 'link'){
      if(isset($group->link) && $group->link){
        $value = $group->link;
      }
      $tpl = $this->getLinkBlock($ident, $id_lang, $id_shop, $value, false);
    }

    return $tpl;
  }


  public function getGroupsForm($id_lang, $id_shop, $id_topmenu, $id_topmenu_column, $groups){


    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'mpm_topmenu/views/templates/hook/groups.tpl');
    $languages = Language::getLanguages(false);


    foreach ($groups as $key => $group) {

      $obj = new topMenuGroup($group['id_topmenu_group']);

      $tpl = $this->getContentByType($id_lang, $id_shop, $obj->type, $obj, $obj->ident);

      $groups[$key]['id_topmenu_column'] = $obj->id_topmenu_column;
      $groups[$key]['id_topmenu'] = $obj->id_topmenu;
      $groups[$key]['ident'] = $obj->ident;
      $groups[$key]['active'] = $obj->active;
      $groups[$key]['title'] = $obj->title;
      $groups[$key]['background_color'] = $obj->background_color;
      $groups[$key]['text_color'] = $obj->text_color;
      $groups[$key]['text_color_hover'] = $obj->text_color_hover;
      $groups[$key]['type'] = $obj->type;
      $groups[$key]['title_front'] = $obj->title_front;
      $groups[$key]['description_before'] = $obj->description_before;
      $groups[$key]['description_after'] = $obj->description_after;
      $groups[$key]['tpl'] = $tpl;

    }

    $data->assign(
      array(
        'groups'              => $groups,
        'id_topmenu_column'   => $id_topmenu_column,
        'id_lang'             => $id_lang,
        'id_shop'             => $id_shop,
        'id_topmenu'          => $id_topmenu,
        'languages'           => $languages,
        'defaultFormLanguage' => $this->default_form_language,
      )
    );
    return $data->fetch();

  }


  public function getColumnsTab($id_lang, $id_shop, $columns, $id_topmenu_column){

      $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'mpm_topmenu/views/templates/hook/tabs_group.tpl');

      $data->assign(
        array(
          'id_topmenu_column' => $id_topmenu_column,
          'columns'           => $columns,
          'id_lang'           => $id_lang,
          'id_shop'           => $id_shop,
        )
      );
      return $data->fetch();

  }


  public function getGroups($id_lang, $id_shop, $id_topmenu_column, $id_topmenu){

    $where = "";

    if($id_topmenu_column){
      $where = 'AND t.id_topmenu_column = ' . (int)$id_topmenu_column ;
    }

    $sql = '
			SELECT tg.id_topmenu_group
      FROM ' . _DB_PREFIX_ . 'topmenu_column as t
      INNER JOIN ' . _DB_PREFIX_ . 'topmenu_group as tg
      ON t.id_topmenu_column = tg.id_topmenu_column
      INNER JOIN ' . _DB_PREFIX_ . 'topmenu_group_lang as tgl
      ON tgl.id_topmenu_group = tg.id_topmenu_group
      WHERE tgl.id_lang = ' . (int)$id_lang . '
      AND tgl.id_shop = ' . (int)$id_shop .'
       AND t.id_topmenu = ' . (int)$id_topmenu .'
       '.$where.'
      ORDER BY tg.position

			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function getColumns($id_lang, $id_shop, $id_topmenu){

    $sql = '
			SELECT *
      FROM ' . _DB_PREFIX_ . 'topmenu_column as t
      INNER JOIN ' . _DB_PREFIX_ . 'topmenu_column_lang as tl
      ON t.id_topmenu_column = tl.id_topmenu_column
      WHERE tl.id_lang = ' . (int)$id_lang . '
      AND tl.id_shop = ' . (int)$id_shop .'
      AND t.id_topmenu = ' . (int)$id_topmenu .'
      ORDER BY t.position

			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }


  public function ajaxProcessSearchProduct(){
    $search = Tools::getValue('q');
    $limit = 50;
    $idLang = Tools::getValue('id_lang');
    $idShop = Tools::getValue('id_shop');
    $where = "";
    $limit_p = '';
    if( $search ){
      $where = " AND (pl.name LIKE '%$search%' OR pl.id_product LIKE '%$search%')";
    }
    if($limit){
      $limit_p = ' LIMIT '.(int)$limit;
    }
    $sql = '
			SELECT pl.name, pl.id_product as id, i.id_image, pl.link_rewrite, p.reference as ref
      FROM ' . _DB_PREFIX_ . 'product_lang as pl
      LEFT JOIN ' . _DB_PREFIX_ . 'image as i
      ON i.id_product = pl.id_product AND i.cover=1
      INNER JOIN ' . _DB_PREFIX_ . 'product as p
      ON p.id_product = pl.id_product
      WHERE pl.id_lang = ' . (int)$idLang . '
      AND pl.id_shop = ' . (int)$idShop . '
      ' . $where . $limit_p. '
			';

    $items = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    foreach($items as $key => $item){
      $items[$key]['image'] = str_replace('http://', Tools::getShopProtocol(), $this->context->link->getImageLink($item['link_rewrite'], $item['id_image'], ''));
    }

    die(json_encode($items));
  }



}