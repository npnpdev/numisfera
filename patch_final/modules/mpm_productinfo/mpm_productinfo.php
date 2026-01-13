<?php
if (!defined('_PS_VERSION_'))
  exit;

require_once(dirname(__FILE__) . '/classes/productInfo.php');


class mpm_productinfo extends Module
{
  public function __construct()
  {
    $this->name = 'mpm_productinfo';
    $this->tab = 'front_office_features';
    $this->version = '1.0.1';
    $this->author = 'MyPrestaModules';
    $this->need_instance = 0;
    $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    $this->bootstrap = true;
    $this->module_key = '0920411ab0a254a68630f7a0559d3a82';

    parent::__construct();

    $this->displayName = $this->l('Block product info');
    $this->description = $this->l('Block product info.');
    $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

    $this->templateFile = 'module:mpm_productinfo/views/templates/hook/block-info.tpl';
  }

  public function install()
  {
    if (!parent::install()
      || !$this->registerHook('header')
      || !$this->registerHook('displayProductInfo')
    )
      return false;

    $this->_createTab();
    $this->installDb();
    $this->_setDataDb();

    return true;
  }

  public function uninstall()
  {
    if (!parent::uninstall() )
      return false;

    $this->_removeTab();
    $this->uninstallDb();

    return true;
  }

  private function uninstallDb()
  {
    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'product_info';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'product_info_lang';
    Db::getInstance()->execute($sql);
  }

  private function _createTab()
  {
    $tab = new Tab();
    $tab->active = 1;
    $tab->class_name = 'AdminBlockProductInfo';
    $tab->name = array();
    foreach (Language::getLanguages(true) as $lang)
      $tab->name[$lang['id_lang']] = 'Block Product Info';
    $tab->id_parent = -1;
    $tab->module = $this->name;
    $tab->add();
  }

  private function _removeTab()
  {
    $id_tab = (int)Tab::getIdFromClassName('AdminBlockProductInfo');
    if ($id_tab)
    {
      $tab = new Tab($id_tab);
      $tab->delete();
    }
  }

  public function installDb()
  {
    // Table  pages
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'product_info';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'product_info(
				id_product_info int(11) unsigned NOT NULL AUTO_INCREMENT,
				active boolean NOT NULL,
				position int(11) unsigned NOT NULL,
			  date_add datetime NULL,
				PRIMARY KEY (`id_product_info`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';

    Db::getInstance()->execute($sql);

    // Table  pages lang
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'product_info_lang';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'product_info_lang(
				id_product_info int(11) unsigned NOT NULL,
				id_lang int(11) unsigned NOT NULL,
				id_shop int(11) unsigned NOT NULL,
				title varchar(255) NOT NULL,
				description varchar(512) NULL,
				PRIMARY KEY(id_product_info, id_shop, id_lang)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
    Db::getInstance()->execute($sql);
  }

  private function _setDataDb(){

    $data = array(
      array('title' => 'PAYMENT', 'description' => 'We accept Visa, MasterCard and American Express.'),
      array('title' => 'FREE SHIPPING', 'description' => 'All orders over $100 free super fast delivery'),
      array('title' => 'BEST PRIEC GUARANTEE', 'description' => 'The best choice for high quality at good prices.'),
      array('title' => 'SHIPPING', 'description' => 'We ship to over 100 countries worldwide through fast and reliab'),
    );

    foreach($data as $value){
      $this->_setItem($value);
    }
  }

  private function _setItem($value){

    $languages = Language::getLanguages(false);
    $obj = new productInfo();

    foreach ($languages as $lang){
      $obj->title[$lang['id_lang']] = $value['title'];
      $obj->description[$lang['id_lang']] =  $value['description'];
    }
    $obj->active = 1;
    $obj->save();
  }

  public function getContent()
  {
    Tools::redirectAdmin($this->context->link->getAdminLink('AdminBlockProductInfo'));
  }

  public function hookHeader() {
    $this->context->controller->registerStylesheet('mpm_productinfo', 'modules/'.$this->name.'/views/css/style_front.css', array('media' => 'all', 'priority' => 900));
    $this->context->controller->registerJavascript('mpm_productinfo', 'modules/'.$this->name.'/views/js/main.js', array('position' => 'bottom', 'priority' => 150));
  }

  public function getProductsInfo($id_lang, $id_shop){
    $sql = '
      SELECT  *
        FROM ' . _DB_PREFIX_ . 'product_info p
        LEFT JOIN ' . _DB_PREFIX_ . 'product_info_lang as pl
        ON p.id_product_info = pl.id_product_info
        WHERE pl.id_lang = ' . $id_lang . '
        AND pl.id_shop = '.$id_shop.'
        AND p.active = 1
        ORDER BY p.position
			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function hookDisplayProductInfo( $params)
  {
    if($this->context->controller->php_self != 'product'){
      return false;
    }

    $ext = 'png';
    $settings = $this->getProductsInfo(Context::getContext()->language->id, Context::getContext()->shop->id);
    foreach($settings as $key=>$value){
      $settings[$key]['image'] = (file_exists(_PS_MODULE_DIR_.'/mpm_productinfo/views/img/'.$value['id_product_info'].'.'.$ext)) ? (_MODULE_DIR_.'mpm_productinfo/views/img/'.$value['id_product_info'].'.'.$ext) : false;
    }

    $count = count($settings);
    $class = 'product-inform-'.$count;

    $this->smarty->assign(
      array(
        'settings' => $settings,
        'class' => $class,
        'id_lang'  => Context::getContext()->language->id,
        'id_shop'  => Context::getContext()->shop->id,
      )
    );
    return $this->fetch($this->templateFile);
  }

}