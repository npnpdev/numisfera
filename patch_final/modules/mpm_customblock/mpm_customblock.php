<?php
if (!defined('_PS_VERSION_'))
  exit;

require_once(dirname(__FILE__) . '/classes/customBlock.php');


class mpm_customblock extends Module
{

  private $_shopId;
  private $_langId;

  public function __construct()
  {
    $this->name = 'mpm_customblock';
    $this->tab = 'front_office_features';
    $this->version = '1.0.1';
    $this->author = 'MyPrestaModules';
    $this->need_instance = 0;
    $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    $this->bootstrap = true;
    $this->module_key = '0920411ab0a254a68630f7a0559d3a82';

    parent::__construct();

    $this->_shopId = Context::getContext()->shop->id;
    $this->_langId = Context::getContext()->language->id;
    $this->displayName = $this->l('Custom block on homepage');
    $this->description = $this->l('Custom block on homepage.');
    $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
  }

  public function install()
  {
    if (!parent::install()
        || !$this->registerHook('actionAdminControllerSetMedia')
      || !$this->registerHook('header')
      || !$this->registerhook('displayHomeContent1')
      || !$this->registerhook('displayHomeContent2')
      || !$this->registerhook('displayHomeContent3')
      || !$this->registerhook('displayHomeContent4')
      || !$this->registerhook('displayHomeContent5')
      || !Configuration::updateValue('GOMAKOIL_CUSTOM_BLOCK_HOOK', 'displayHomeContent5')
    )
      return false;

    $this->_createTab();
    $this->installDb();
    $this->_setDataDb();

    return true;
  }

  public function uninstall()
  {
    if (!parent::uninstall())
      return false;

    $this->_removeTab();
    $this->uninstallDb();

    return true;
  }

    public function hookActionAdminControllerSetMedia()
    {
        if (Tools::getValue('controller') != 'AdminCustomBlock') {
            return false;
        }

        $this->context->controller->addCSS([
            _PS_MODULE_DIR_.'mpm_customblock/views/css/mpm_customblock_admin.css'
        ]);

        $this->context->controller->addJS([
            _PS_MODULE_DIR_.'mpm_customblock/views/js/mpm_customblock_admin.js'
        ]);
    }

  private function uninstallDb()
  {
    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'customblock';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'customblock_lang';
    Db::getInstance()->execute($sql);
  }

  private function _createTab()
  {
    $tab = new Tab();
    $tab->active = 1;
    $tab->class_name = 'AdminCustomBlock';
    $tab->name = array();
    foreach (Language::getLanguages(true) as $lang)
      $tab->name[$lang['id_lang']] = 'Custom Block';
    $tab->id_parent = -1;
    $tab->module = $this->name;
    $tab->add();
  }

  private function _removeTab()
  {
    $id_tab = (int)Tab::getIdFromClassName('AdminCustomBlock');
    if ($id_tab)
    {
      $tab = new Tab($id_tab);
      $tab->delete();
    }
  }


  public function installDb()
  {
    // Table  pages
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'customblock';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'customblock(
				id_customblock int(11) unsigned NOT NULL AUTO_INCREMENT,
				active boolean NOT NULL,
				position int(11) unsigned NOT NULL,
			  date_add datetime NULL,
				PRIMARY KEY (`id_customblock`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';

    Db::getInstance()->execute($sql);

    // Table  pages lang
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'customblock_lang';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'customblock_lang(
				id_customblock int(11) unsigned NOT NULL,
				id_lang int(11) unsigned NOT NULL,
				id_shop int(11) unsigned NOT NULL,
				title varchar(255) NOT NULL,
				description varchar(512) NULL,
				PRIMARY KEY(id_customblock, id_shop, id_lang)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
    Db::getInstance()->execute($sql);

  }


  private function _setDataDb(){

    $data = array(
      array('title' => 'Payment', 'description' => 'We accept Visa, MasterCard and American Express.'),
      array('title' => 'Free shipping', 'description' => 'All orders over $100 free super fast delivery'),
      array('title' => 'Best priec guarantee', 'description' => 'The best choice for high quality at good prices.'),
      array('title' => 'Shipping', 'description' => 'We ship to over 100 countries worldwide through fast and reliab'),
    );

    foreach($data as $value){
      $this->_setItem($value);
    }
  }


  private function _setItem($value){

    $languages = Language::getLanguages(false);
    $obj = new customBlock();

    foreach ($languages as $lang){
      $obj->title[$lang['id_lang']] = $value['title'];
      $obj->description[$lang['id_lang']] =  $value['description'];
    }
    $obj->active = 1;
    $obj->save();

  }


  public function getContent()
  {
    Tools::redirectAdmin($this->context->link->getAdminLink('AdminCustomBlock'));
  }


  public function hookHeader() {
    $this->context->controller->registerStylesheet('mpm_customblock', 'modules/'.$this->name.'/views/css/mpm_customblock.css', array('media' => 'all', 'priority' => 900));
  }


  public function hookDisplayHomeContent1()
  {
    $hook = Configuration::get('GOMAKOIL_CUSTOM_BLOCK_HOOK');
    if(($hook !== 'displayHomeContent1')){
      return false;
    }
    $this->smarty->assign($this->getVariables());
    return $this->display(__FILE__, 'views/templates/hook/customblock.tpl');
  }

  public function hookDisplayHomeContent2()
  {
    $hook = Configuration::get('GOMAKOIL_CUSTOM_BLOCK_HOOK');
    if(($hook !== 'displayHomeContent2')){
      return false;
    }
    $this->smarty->assign($this->getVariables());
    return $this->display(__FILE__, 'views/templates/hook/customblock.tpl');
  }

  public function hookDisplayHomeContent3()
  {
    $hook = Configuration::get('GOMAKOIL_CUSTOM_BLOCK_HOOK');
    if(($hook !== 'displayHomeContent3')){
      return false;
    }
    $this->smarty->assign($this->getVariables());
    return $this->display(__FILE__, 'views/templates/hook/customblock.tpl');
  }

  public function hookDisplayHomeContent4()
  {
    $hook = Configuration::get('GOMAKOIL_CUSTOM_BLOCK_HOOK');
    if(($hook !== 'displayHomeContent4')){
      return false;
    }
    $this->smarty->assign($this->getVariables());
    return $this->display(__FILE__, 'views/templates/hook/customblock.tpl');
  }

  public function hookDisplayHomeContent5()
  {
    $hook = Configuration::get('GOMAKOIL_CUSTOM_BLOCK_HOOK');
    if(($hook !== 'displayHomeContent5')){
      return false;
    }
    $this->smarty->assign($this->getVariables());
    return $this->display(__FILE__, 'views/templates/hook/customblock.tpl');
  }

  public function getVariables()
  {
    $obj = new CustomBlock();
    $items = $obj->getCustomBlock($this->_langId, $this->_shopId);
    foreach ($items as $key => $item) {
      $items[$key]['image'] = (file_exists(_PS_MODULE_DIR_.'/mpm_customblock/views/img/'.$item['id_customblock'].'.png')) ? (_MODULE_DIR_.'mpm_customblock/views/img/'.$item['id_customblock'].'.png') : false;
    }
    return array(
      'id_shop' => $this->_shopId,
      'id_lang' => $this->_langId,
      'items'   => $items,
    );
  }

}