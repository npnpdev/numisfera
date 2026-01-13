<?php
/*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
  exit;
}

class Mpm_header extends Module
{

  private $_idShop;
  private $_idLang;

  public function __construct()
  {
    $this->name = 'mpm_header';
    $this->version = '1.0.2';
    $this->author = 'PrestaShop';
    $this->need_instance = 0;
    $this->tab = 'front_office_features';
    $this->bootstrap = true;
    parent::__construct();

    $this->displayName =   $this->l('Header block');
    $this->description =   $this->l('Displays a header on your shop.');

    $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
    $this->_idShop = Context::getContext()->shop->id;
    $this->_idLang = Context::getContext()->language->id;
  }

  public function install()
  {

    if (!parent::install()
      || !$this->registerHook('header')
      || !Configuration::updateValue('BLOCK_CATEG_ROOT_CATEGORY', 0)
      || !$this->registerhook('displayMpmHeader')
    )
      return false;

    return true;
  }

  public function uninstall()
  {
    return parent::uninstall();
  }


  public function hookDisplayHeader($params)
  {
    $this->context->controller->registerStylesheet('mpm_header', 'modules/mpm_header/views/css/header_style.css', array('media' => 'all', 'priority' => 900));
    $this->context->controller->registerStylesheet('header_responsive', 'modules/mpm_header/views/css/header_responsive.css', array('media' => 'all', 'priority' => 900));
    $this->context->controller->registerJavascript('mpm_header', 'modules/'.$this->name.'/views/js/mpm_header.js', array('position' => 'bottom', 'priority' => 150));
    $this->context->controller->registerJavascript('elevateZoom', 'modules/'.$this->name.'/views/js/jquery.elevateZoom-3.0.8.min.js', array('position' => 'bottom', 'priority' => 150));
    $this->context->controller->registerJavascript('elevatezoom2', 'modules/'.$this->name.'/views/js/jquery.elevatezoom.js', array('position' => 'bottom', 'priority' => 150));
    $this->context->controller->registerJavascript('xzoom', 'modules/'.$this->name.'/views/js/xzoom.min.js', array('position' => 'bottom', 'priority' => 150));
    $product_zoom = Configuration::get('GOMAKOIL_PRODUCT_ZOOM');

    Media::addJsDefL('product_zoom', $product_zoom);

  }

  public function hookDisplayMpmHeader()
  {
    $this->context->smarty->assign(
      array(
        'id_shop' => $this->_idShop,
        'id_lang' => $this->_idLang,
      ));
    return $this->display(__FILE__, 'views/templates/hook/header_block.tpl');
  }

}
