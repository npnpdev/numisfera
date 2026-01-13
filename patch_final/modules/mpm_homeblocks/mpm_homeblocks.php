<?php
if (!defined('_PS_VERSION_'))
  exit;

require_once(dirname(__FILE__) . '/classes/homeBlocks.php');

class mpm_homeblocks extends Module
{
  private $_idShop;
  private $_idLang;

  public function __construct()
  {
    $this->name = 'mpm_homeblocks';
    $this->tab = 'front_office_features';
    $this->version = '1.0.1';
    $this->author = 'MyPrestaModules';
    $this->need_instance = 0;
    $this->bootstrap = true;

    parent::__construct();

    $this->displayName = $this->l('Home blocks');
    $this->description = $this->l('Home blocks');
    $this->_idShop = Context::getContext()->shop->id;
    $this->_idLang = Context::getContext()->language->id;
  }

  public function install()
  {
    if (!parent::install()
      || !$this->registerHook('header')
      || !$this->registerhook('displayHomeContent2')
    )
      return false;

    $this->_createTab('AdminBlocksHome', 'Home blocks');
    $this->_installDb();
    $this->_setDataDb();


    return true;
  }

  public function uninstall()
  {
    if (  !parent::uninstall()  )
      return false;


    $this->_removeTab('AdminBlocksHome');
    $this->_uninstallDb();
    return true;
  }

  private function _createTab($class_name, $name)
  {
    $tab = new Tab();
    $tab->active = 1;
    $tab->class_name = $class_name;
    $tab->name = array();
    foreach (Language::getLanguages(true) as $lang)
      $tab->name[$lang['id_lang']] = $name;
    $tab->id_parent = -1;
    $tab->module = $this->name;
    $tab->add();
  }

  private function _removeTab($class_name)
  {
    $id_tab = (int)Tab::getIdFromClassName($class_name);
    if ($id_tab)
    {
      $tab = new Tab($id_tab);
      $tab->delete();
    }
  }

  private function _installDb()
  {
    // Table  pages
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'homeblocks';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'homeblocks(
				id_homeblocks int(11) unsigned NOT NULL AUTO_INCREMENT,
				position int(11) unsigned NOT NULL,
				active boolean NOT NULL,
		
				background_color varchar(255) NULL,
			  min_height int(11) unsigned NOT NULL,
        width int(11) unsigned NOT NULL,
				date_add datetime NULL,
				PRIMARY KEY (`id_homeblocks`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';

    Db::getInstance()->execute($sql);

    // Table  pages lang
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'homeblocks_lang';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'homeblocks_lang(
				id_homeblocks int(11) unsigned NOT NULL,
				id_lang int(11) unsigned NOT NULL,
				id_shop int(11) unsigned NOT NULL,
				description TEXT  NOT NULL,
	
				PRIMARY KEY(id_homeblocks, id_shop, id_lang)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
    Db::getInstance()->execute($sql);
  }

  private function _uninstallDb()
  {
    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'homeblocks';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'homeblocks_lang';
    Db::getInstance()->execute($sql);
  }


  private function _setDataDb(){

    $desc_1 = '<p></p><h3><em><span style="color: #878786;">New collection</span></em></h3><h1><span style="font-size: 30px;">Sun glasses</span></h1><p></p><p ><a style="margin-top: 15px;" class="btn btn-primary-gomakoil" href="#">Shop now</a></p>';
    $desc_2 = '<p></p><h3><span style="color: #878786;"><em>New collection</em></span></h3><h1><span style="color: #ffffff; font-size: 30px;">Sun glasses</span></h1><p></p><p><a style="margin-top: 15px;" class="btn btn-primary-gomakoil" href="#">Shop now</a></p>';

    $data = array(
      array('description' => $desc_1, 'width' => '33', 'background_color' => '#ffffff'),
      array('description' => $desc_2, 'width' => '33', 'background_color' => '#000000'),
      array('description' => $desc_2, 'width' => '34', 'background_color' => '#342d0a'),

    );

    foreach($data as $value){
      $this->_setItem($value);
    }
  }


  private function _setItem($value){

    $languages = Language::getLanguages(false);
    $obj = new homeBlocks();
    foreach ($languages as $lang){
      $obj->description[$lang['id_lang']] = $value['description'];
    }

    $obj->min_height = 228;
    $obj->background_color = $value['background_color'];
    $obj->width = $value['width'];
    $obj->active = 1;
    $obj->save();
  }

  public function getContent()
  {
    Tools::redirectAdmin($this->context->link->getAdminLink('AdminBlocksHome'));
  }

  public function hookDisplayHeader()
  {
    $this->context->controller->registerStylesheet('mpm_homeblocks', 'modules/'.$this->name.'/views/css/mpm_homeblocks.css',  array('media' => 'all', 'priority' => 900));
  }

  public function hookDisplayHomeContent2()
  {

    $settings = $this->getSetiingsBlocks($this->_idLang, $this->_idShop);
    if(!$settings){
      return false;
    }

    foreach ($settings as $key => $value){
      $settings[$key]['image'] = (file_exists(_PS_MODULE_DIR_.'/mpm_homeblocks/views/img/'.$value['id_homeblocks'].'.png')) ? (_MODULE_DIR_.'mpm_homeblocks/views/img/'.$value['id_homeblocks'].'.png') : false;
    }

    $this->context->smarty->assign(
      array(
        'settings' => $settings,
        'id_shop'  => $this->_idShop,
        'id_lang'  => $this->_idLang,
      )
    );
    return $this->display(__FILE__, 'views/templates/hook/banners.tpl');
  }


  public function getSetiingsBlocks($id_lang, $id_shop){
    $sql = '
			SELECT *
      FROM ' . _DB_PREFIX_ . 'homeblocks as b
      INNER JOIN ' . _DB_PREFIX_ . 'homeblocks_lang as bl
      ON b.id_homeblocks = bl.id_homeblocks
      WHERE bl.id_lang = ' . (int)$id_lang . '
      AND bl.id_shop = ' . (int)$id_shop .'
      AND b.active = 1
      
			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }


}