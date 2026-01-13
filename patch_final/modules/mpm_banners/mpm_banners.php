<?php
if (!defined('_PS_VERSION_'))
  exit;

require_once(dirname(__FILE__) . '/classes/homeBanners.php');

class mpm_banners extends Module
{
  private $_idShop;
  private $_idLang;
  private $_homeBanners;

  public function __construct()
  {
    $this->name = 'mpm_banners';
    $this->tab = 'front_office_features';
    $this->version = '1.0.1';
    $this->author = 'MyPrestaModules';
    $this->need_instance = 0;
    $this->bootstrap = true;

    parent::__construct();

    $this->displayName = $this->l('Banners');
    $this->description = $this->l('Banners');
    $this->_idShop = Context::getContext()->shop->id;
    $this->_idLang = Context::getContext()->language->id;
    $this->_homeBanners = new homeBanners();
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
    )
      return false;

    $this->_createTab('AdminBanners', 'Banners');
    $this->_installDb();
    $this->_setDataDb();


    return true;
  }

  public function uninstall()
  {
    if (  !parent::uninstall()  )
      return false;

    $this->_removeImages();
    $this->_removeTab('AdminBanners');
    $this->_uninstallDb();
    return true;
  }

    public function hookActionAdminControllerSetMedia()
    {
        if (Tools::getValue('controller') != 'AdminBanners') {
            return false;
        }

        $this->context->controller->addCSS(array(
            _PS_MODULE_DIR_.'mpm_banners/views/css/mpm_banners_admin.css'
        ));
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
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'banners';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'banners(
				id_banners int(11) unsigned NOT NULL AUTO_INCREMENT,
				position int(11) unsigned NOT NULL,
				active boolean NOT NULL,
				hook varchar(255) NULL,
				background_color_left varchar(255) NULL,
				background_color_right varchar(255) NULL,
			  min_height int(11) unsigned NOT NULL,
        width_block_left int(11) unsigned NOT NULL,
        width_description_left int(11) unsigned NOT NULL,
        width_block_right int(11) unsigned NOT NULL,
        width_description_right int(11) unsigned NOT NULL,
        position_description_left varchar(255) NULL,
        position_description_right varchar(255) NULL,
				date_add datetime NULL,
				PRIMARY KEY (`id_banners`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';

    Db::getInstance()->execute($sql);

    // Table  pages lang
    $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'banners_lang';
    Db::getInstance()->execute($sql);

    $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'banners_lang(
				id_banners int(11) unsigned NOT NULL,
				id_lang int(11) unsigned NOT NULL,
				id_shop int(11) unsigned NOT NULL,
				description_left TEXT  NOT NULL,
				description_right TEXT  NOT NULL,
				PRIMARY KEY(id_banners, id_shop, id_lang)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
    Db::getInstance()->execute($sql);
  }

  private function _uninstallDb()
  {
    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'banners';
    Db::getInstance()->execute($sql);

    $sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'banners_lang';
    Db::getInstance()->execute($sql);
  }

  public function _removeImages(){
    $settings = $this->_homeBanners->getSetiingsItem($this->_idLang, $this->_idShop);
    foreach ($settings as $value){

      if(file_exists(_PS_MODULE_DIR_.'/mpm_banners/views/img/'.$value['id_banners'].'_left.png')){
        unlink(_PS_MODULE_DIR_ . 'mpm_banners/views/img/'.$value['id_banners'].'_left.png');
      }
      if(file_exists(_PS_MODULE_DIR_.'/mpm_banners/views/img/'.$value['id_banners'].'_right.png')){
        unlink(_PS_MODULE_DIR_ . 'mpm_banners/views/img/'.$value['id_banners'].'_right.png');
      }

    }
  }

  private function _setDataDb(){



    $left1 = '<p style="margin-top: 10px; text-align: left;"><span style="font-size: 19px; color: #9b9b9b;"></span><span style="color: #9b9b9b;"><span style="font-size: 17px;">Limited time offer</span></span><span style="font-size: 17px; color: #9b9b9b;"></span></p>
              <p style="margin-top: 15px; text-align: left;"><span style="color: #ffffff;"><span style="font-size: 40px;">SUMMER <em>Sale</em></span></span></p>
              <p style="margin-top: 10px; text-align: left;"><span style="color: #d09f67;"><span><span style="font-size: 20px;">SAVE UP TO 50%</span></span></span></p>
              <p style="margin-top: 25px; text-align: left;"><span style="color: #ffffff;"><span style="font-size: 16px;">The new Versace Vanitas handbags collection is really amazing, and according to Donatella Versace it reflects the true heritage of the brand. Its a blend of the most iconoclassic elements such as the Medusa or the Barocco. A luxury accessories line designed for today woomen. And I just love it!</span></span></p>
              <p style="margin-top: 10px; text-align: left;"><a class="btn btn-primary-gomakoil" href="#">Shop now</a></p>';

    $right1 = '<h1 style="text-align: center;"></h1><h1 style="text-align: center; margin-top: 140px;"><span style="font-weight: bolder; color: #ffffff; font-family: \'Noto Sans\', sans-serif; font-size: 32px; text-align: center; text-transform: uppercase;">SPECIAL OFFER</span></h1>';

    $left2 = '<h1 style="text-align: center;"></h1><h1 style="text-align: center; margin-top: 140px;"><span style="font-weight: bolder; color: #ffffff; font-family: \'Noto Sans\', sans-serif; font-size: 32px; text-align: center; text-transform: uppercase;">OUR STORE</span></h1>';

    $right2 = '<p  style="margin-top: 53px;"><span style="color: #ffffff; font-size: 16px;">The new Versace Vanitas handbags collection is really amazing, and according to Donatella Versace it reflects the true heritage of the brand. It\'s a blend of the most iconoclassic elements such as the Medusa or the Barocco. A luxury accessories line designed for today women. And I just love it! The new Versace Vanitas handbags collection is really amazing, and according to Donatella Versace it reflects the true heritage of the brand. It\'s a blend of the most iconoclassic elements such as the Medusa or the Barocco. A luxury accessories line designed for today women. And I just love it! The new Versace Vanitas handbags collection is really amazing, and according to Donatella Versace it reflects the true heritage of the brand. It\'s a blend of the most iconoclassic elements such as the Medusa or the Barocco. A luxury accessories line designed for today women. And I just love it!</span></p><p></p>';

    $data = array(
      array('img' => '1', 'background_color_left' => '#202020', 'background_color_right' => '#000000', 'hook' => 'displayHomeContent2', 'description_left' =>  $left1, 'description_right' => $right1, 'width_block_left' => '70', 'width_block_right' => '30', 'width_description_left' => '540', 'width_description_right' => '290', 'position_description_left' => 'left', 'position_description_right' => 'center' ),
      array('img' => '2', 'background_color_left' => '#d0a369', 'background_color_right' => '#d0a369', 'hook' => 'displayHomeContent3', 'description_left' => $left2, 'description_right' => $right2, 'width_block_left' => '30', 'width_block_right' => '70', 'width_description_left' => '290', 'width_description_right' => '840', 'position_description_left' => 'center', 'position_description_right' => 'center' ),

    );

    foreach($data as $value){
      $this->_setItem($value);
    }
  }


  private function _setItem($value){

    $languages = Language::getLanguages(false);
    $obj = new homeBanners();
    foreach ($languages as $lang){
      $obj->description_left[$lang['id_lang']] = $value['description_left'];
      $obj->description_right[$lang['id_lang']] = $value['description_right'];
    }
    $obj->hook = $value['hook'];
    $obj->min_height = 350;
    $obj->background_color_left = $value['background_color_left'];
    $obj->background_color_right = $value['background_color_right'];
    $obj->width_block_left = $value['width_block_left'];
    $obj->width_description_left = $value['width_description_left'];
    $obj->width_block_right = $value['width_block_right'];
    $obj->width_description_right = $value['width_description_right'];
    $obj->position_description_left = $value['position_description_left'];
    $obj->position_description_right = $value['position_description_right'];
    $obj->active = 1;
    $obj->save();

    if($value['img'] == 1){
      copy(dirname(__FILE__).'/views/img/def/'.$value['img'].'.png',  dirname(__FILE__).'/views/img/1_left.png');
    }

    if($value['img'] == 2){
      copy(dirname(__FILE__).'/views/img/def/'.$value['img'].'.png',  dirname(__FILE__).'/views/img/2_right.png');
    }


  }

  public function getContent()
  {
    Tools::redirectAdmin($this->context->link->getAdminLink('AdminBanners'));
  }

  public function hookDisplayHeader($params)
  {
    $this->context->controller->registerStylesheet('mpm_banners', 'modules/'.$this->name.'/views/css/mpm_banners.css',  array('media' => 'all', 'priority' => 900));
    $this->context->controller->registerJavascript('mpm_banners', 'modules/'.$this->name.'/views/js/mpm_banners.js', array('position' => 'bottom', 'priority' => 150));
  }



  public function hookDisplayHomeContent1()
  {
    $settings = $this->_homeBanners->getSetiingsItem($this->_idLang, $this->_idShop, 'displayHomeContent1');
    if(!$settings){
      return false;
    }

    foreach ($settings as $key => $value){
      $settings[$key]['image_left'] = (file_exists(_PS_MODULE_DIR_.'/mpm_banners/views/img/'.$value['id_banners'].'_left.png')) ? (_MODULE_DIR_.'mpm_banners/views/img/'.$value['id_banners'].'_left.png') : false;
      $settings[$key]['image_right'] = (file_exists(_PS_MODULE_DIR_.'/mpm_banners/views/img/'.$value['id_banners'].'_right.png')) ? (_MODULE_DIR_.'mpm_banners/views/img/'.$value['id_banners'].'_right.png') : false;
    }

    if(!$settings){
      return false;
    }


    $this->context->smarty->assign(
      array(
        'settings' => $settings,
        'id_shop'  => $this->_idShop,
        'id_lang'  => $this->_idLang,
      )
    );
    return $this->display(__FILE__, 'views/templates/hook/itemBanner.tpl');
  }

  public function hookDisplayHomeContent2()
  {
    $settings = $this->_homeBanners->getSetiingsItem($this->_idLang, $this->_idShop, 'displayHomeContent2');
    if(!$settings){
      return false;
    }

    foreach ($settings as $key => $value){
      $settings[$key]['image_left'] = (file_exists(_PS_MODULE_DIR_.'/mpm_banners/views/img/'.$value['id_banners'].'_left.png')) ? (_MODULE_DIR_.'mpm_banners/views/img/'.$value['id_banners'].'_left.png') : false;
      $settings[$key]['image_right'] = (file_exists(_PS_MODULE_DIR_.'/mpm_banners/views/img/'.$value['id_banners'].'_right.png')) ? (_MODULE_DIR_.'mpm_banners/views/img/'.$value['id_banners'].'_right.png') : false;
    }

    if(!$settings){
      return false;
    }

    $this->context->smarty->assign(
      array(
        'settings' => $settings,
        'id_shop'  => $this->_idShop,
        'id_lang'  => $this->_idLang,
      )
    );
    return $this->display(__FILE__, 'views/templates/hook/itemBanner.tpl');
  }

  public function hookDisplayHomeContent3()
  {
    $settings = $this->_homeBanners->getSetiingsItem($this->_idLang, $this->_idShop, 'displayHomeContent3');
    if(!$settings){
      return false;
    }

    foreach ($settings as $key => $value){
      $settings[$key]['image_left'] = (file_exists(_PS_MODULE_DIR_.'/mpm_banners/views/img/'.$value['id_banners'].'_left.png')) ? (_MODULE_DIR_.'mpm_banners/views/img/'.$value['id_banners'].'_left.png') : false;
      $settings[$key]['image_right'] = (file_exists(_PS_MODULE_DIR_.'/mpm_banners/views/img/'.$value['id_banners'].'_right.png')) ? (_MODULE_DIR_.'mpm_banners/views/img/'.$value['id_banners'].'_right.png') : false;
    }

    if(!$settings){
      return false;
    }

    $this->context->smarty->assign(
      array(
        'settings' => $settings,
        'id_shop'  => $this->_idShop,
        'id_lang'  => $this->_idLang,
      )
    );
    return $this->display(__FILE__, 'views/templates/hook/itemBanner.tpl');
  }

  public function hookDisplayHomeContent4()
  {
    $settings = $this->_homeBanners->getSetiingsItem($this->_idLang, $this->_idShop, 'displayHomeContent4');
    if(!$settings){
      return false;
    }

    foreach ($settings as $key => $value){
      $settings[$key]['image_left'] = (file_exists(_PS_MODULE_DIR_.'/mpm_banners/views/img/'.$value['id_banners'].'_left.png')) ? (_MODULE_DIR_.'mpm_banners/views/img/'.$value['id_banners'].'_left.png') : false;
      $settings[$key]['image_right'] = (file_exists(_PS_MODULE_DIR_.'/mpm_banners/views/img/'.$value['id_banners'].'_right.png')) ? (_MODULE_DIR_.'mpm_banners/views/img/'.$value['id_banners'].'_right.png') : false;
    }

    if(!$settings){
      return false;
    }

    $this->context->smarty->assign(
      array(
        'settings' => $settings,
        'id_shop'  => $this->_idShop,
        'id_lang'  => $this->_idLang,
      )
    );
    return $this->display(__FILE__, 'views/templates/hook/itemBanner.tpl');
  }

  public function hookDisplayHomeContent5()
  {
    $settings = $this->_homeBanners->getSetiingsItem($this->_idLang, $this->_idShop, 'displayHomeContent5');
    if(!$settings){
      return false;
    }

    foreach ($settings as $key => $value){
      $settings[$key]['image_left'] = (file_exists(_PS_MODULE_DIR_.'/mpm_banners/views/img/'.$value['id_banners'].'_left.png')) ? (_MODULE_DIR_.'mpm_banners/views/img/'.$value['id_banners'].'_left.png') : false;
      $settings[$key]['image_right'] = (file_exists(_PS_MODULE_DIR_.'/mpm_banners/views/img/'.$value['id_banners'].'_right.png')) ? (_MODULE_DIR_.'mpm_banners/views/img/'.$value['id_banners'].'_right.png') : false;
    }

    if(!$settings){
      return false;
    }

    $this->context->smarty->assign(
      array(
        'settings' => $settings,
        'id_shop'  => $this->_idShop,
        'id_lang'  => $this->_idLang,
      )
    );
    return $this->display(__FILE__, 'views/templates/hook/itemBanner.tpl');
  }



}