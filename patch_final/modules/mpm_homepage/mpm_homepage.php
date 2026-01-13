<?php
/**
 * Created by PhpStorm.
 * User: maskc_000
 * Date: 08.11.13
 * Time: 10:59
 */

class mpm_homepage extends Module{

  private $_idShop;
  private $_idLang;

  public function __construct()
  {
    $this->name = 'mpm_homepage';
    $this->tab = 'front_office_features';
    $this->version = '1.0.1';
    $this->author = 'MyPrestaModules';
    $this->need_instance = 0;

    parent::__construct(); // The parent construct is required for translations

    $this->displayName = $this->l('Home page');
    $this->description = $this->l('“Home page content.');
    $this->_idShop = Context::getContext()->shop->id;
    $this->_idLang = Context::getContext()->language->id;
  }


  public function install()
  {
    if (!parent::install()
      || !$this->registerhook('displayHomePageContent')

    ){
      return false;
    }
    return true;
  }

  public function uninstall()
  {
    if (parent::uninstall()){
      return true;
    }
    return false;
  }

  public function hookDisplayHomePageContent(){

    $this->context->smarty->assign(
      array(
        'id_shop' => $this->_idShop,
        'id_lang' => $this->_idLang,
      ));
    return $this->display(__FILE__, 'views/templates/hook/homeBlock.tpl');
  }

}
