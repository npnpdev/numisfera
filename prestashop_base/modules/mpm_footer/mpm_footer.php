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

class Mpm_footer extends Module
{

  private $_idShop;
  private $_idLang;
  private $_imgDir;

  public function __construct()
  {
    $this->name = 'mpm_footer';
    $this->version = '1.0.1';
    $this->author = 'PrestaShop';
    $this->need_instance = 0;
    $this->tab = 'front_office_features';
    $this->bootstrap = true;
    parent::__construct();

    $this->displayName =   $this->l('Footer block');
    $this->description =   $this->l('Displays a footer on your shop.');

    $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
    $this->_idShop = Context::getContext()->shop->id;
    $this->_idLang = Context::getContext()->language->id;

    $this->_imgDir = _PS_MODULE_DIR_ . 'mpm_footer/views/img/';

  }

  public function install()
  {
    if (!parent::install()
      || !$this->registerHook('header')
      || !$this->registerhook('displayMpmFooter')
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

  }

  public function getContent()
  {
    return $this->postProcess().$this->renderForm();
  }



  public function postProcess()
  {

    if (Tools::getValue('deleteImageFooter')) {

      unlink($this->_imgDir.'background.png');

    }

    if (Tools::isSubmit('submitSaveFooter')) {

      $this->uploadImages('background');
      Configuration::updateValue('BACKGROUND_COLOR_FOOTER', Tools::getValue('BACKGROUND_COLOR_FOOTER'));
      Configuration::updateValue('COLOR_FOOTER', Tools::getValue('COLOR_FOOTER'));
      Configuration::updateValue('COLOR_HOVER_FOOTER', Tools::getValue('COLOR_HOVER_FOOTER'));

      return $this->displayConfirmation($this->l('The settings have been updated.'));
    }

    return '';
  }


  public function renderForm()
  {

    $image = $this->_imgDir.'background.png';
    $image_url = ImageManager::thumbnail($image, 'background.png', 350, 'png', true, true);
    $image_size = file_exists($image) ? filesize($image) / 1000 : false;

    $fields_form = array(
      'form' => array(
        'legend' => array(
          'title' => $this->l('Settings'),
          'icon' => 'icon-cogs'
        ),
        'input' => array(
          array(
            'type' => 'color',
            'label' => 'Background color',
            'name' => 'BACKGROUND_COLOR_FOOTER'
          ),
          array(
            'type' => 'color',
            'label' => 'Color',
            'name' => 'COLOR_FOOTER'
          ),
          array(
            'type' => 'color',
            'label' => 'Color hover',
            'name' => 'COLOR_HOVER_FOOTER'
          ),
          array(
            'type' => 'file',
            'label' => $this->l('Background image'),
            'name' => 'file',
            'display_image' => true,
            'image' => $image_url ? $image_url : false,
            'size' => $image_size,
            'delete_url' => $this->context->link->getAdminLink('AdminModules', true).'&configure='.$this->name.'&module_name='.$this->name.'&deleteImageFooter=1',
          ),
        ),
        'submit' => array(
          'title' => $this->l('Save')
        )
      ),
    );

    $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

    $helper = new HelperForm();
    $helper->show_toolbar = false;
    $helper->table = $this->table;
    $helper->default_form_language = $lang->id;
    $helper->module = $this;
    $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
    $helper->identifier = $this->identifier;
    $helper->submit_action = 'submitSaveFooter';
    $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->tpl_vars = array(
      'fields_value' => $this->getConfigFieldsValues(),
      'languages' => $this->context->controller->getLanguages(),
      'id_language' => $this->context->language->id
    );

    return $helper->generateForm(array($fields_form));
  }

  public function getConfigFieldsValues()
  {

    $fields = array();

    $fields['BACKGROUND_COLOR_FOOTER']= Tools::getValue('BACKGROUND_COLOR_FOOTER', Configuration::get('BACKGROUND_COLOR_FOOTER'));
    $fields['COLOR_FOOTER']= Tools::getValue('COLOR_FOOTER', Configuration::get('COLOR_FOOTER'));
    $fields['COLOR_HOVER_FOOTER']= Tools::getValue('COLOR_HOVER_FOOTER', Configuration::get('COLOR_HOVER_FOOTER'));

    return $fields;
  }

  protected function uploadImages($id)
  {
      if (isset($_FILES['file']['tmp_name']) && !empty($_FILES['file']['tmp_name'])) {
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
      }


    return true;
  }



  public function hookDisplayMpmFooter()
  {

    $background_color = Configuration::get('BACKGROUND_COLOR_FOOTER');
    $color = Configuration::get('COLOR_FOOTER');
    $hover = Configuration::get('COLOR_HOVER_FOOTER');

    $image = (file_exists(_PS_MODULE_DIR_.'/mpm_footer/views/img/background.png')) ? (_MODULE_DIR_.'mpm_footer/views/img/background.png') : false;

    $this->context->smarty->assign(
      array(
        'id_shop'          => $this->_idShop,
        'id_lang'          => $this->_idLang,
        'image'            => $image,
        'background_color' => $background_color,
        'color'            => $color,
        'hover'            => $hover,
      ));

    return $this->display(__FILE__, 'views/templates/hook/footer_block.tpl');
  }





}
