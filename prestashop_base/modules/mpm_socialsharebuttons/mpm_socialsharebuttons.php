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


use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;


class Mpm_socialsharebuttons extends Module
{
  private $templateFile;
  private $_html;
  private $_defaultFields;

  public function __construct()
  {
    $this->name = 'mpm_socialsharebuttons';
    $this->version = '1.0.1';
    $this->author = 'PrestaShop';
    $this->need_instance = 0;
    $this->tab = 'front_office_features';
    $this->bootstrap = true;
    parent::__construct();

    $this->displayName = $this->l('Social share buttons');
    $this->description = $this->l('Social share buttons.', array());

    $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);

    $this->templateFile = 'module:mpm_socialsharebuttons/views/templates/hook/buttonshare.tpl';


    $this->_defaultFields = array(
      'button_facebook'          => 1,
      'button_twitter'           => 1,
      'button_googleplus'        => 1,
      'button_linkedin'          => 1,
      'button_email'             => 0,
      'button_pinterest'         => 1,
      'button_pocket'            => 0,
      'button_tumblr'            => 0,
      'button_reddit'            => 0,
      'button_hackernews'        => 0,
    );

  }

  public function install()
  {

    if (!parent::install()
      || !$this->registerHook('displayHeader')
      || !$this->registerHook('displayShareButton')
    )
      return false;

    Configuration::updateValue('GOMAKOIL_SHARE_BUTTONS', serialize($this->_defaultFields));

    return true;
  }


  public function uninstall()
  {

    if ( !parent::uninstall()
      || !Configuration::deleteByName('GOMAKOIL_SHARE_BUTTONS')
    )
      return false;

    return true;
  }

  public function getContent()
  {
    $this->_postProcess();
    $this->displayForm();
    return $this->_html;
  }

  public function displayForm()
  {
    $fields_form = array();

    $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
    $fields_form[0]['form'] = array(
      'legend' => array(
        'title' => $this->l('Settings'),
        'icon' => 'icon-cogs'
      ),
      'input' => array(
          array(
            'type' => 'switch',
            'label' => $this->l('Facebook'),
            'name' => 'button_facebook',
            'is_bool' => true,
            'tab' => 'soc_button_settings',
            'values' => array(
              array(
                'id' => 'display_on',
                'value' => 1,
                'label' => $this->l('Yes')),
              array(
                'id' => 'display_off',
                'value' => 0,
                'label' => $this->l('No')),
            ),
          ),
          array(
            'type' => 'switch',
            'label' => $this->l('Twitter'),
            'name' => 'button_twitter',
            'is_bool' => true,
            'tab' => 'soc_button_settings',
            'values' => array(
              array(
                'id' => 'display_on',
                'value' => 1,
                'label' => $this->l('Yes')),
              array(
                'id' => 'display_off',
                'value' => 0,
                'label' => $this->l('No')),
            ),
          ),
          array(
            'type' => 'switch',
            'label' => $this->l('Google +'),
            'name' => 'button_googleplus',
            'is_bool' => true,
            'tab' => 'soc_button_settings',
            'values' => array(
              array(
                'id' => 'display_on',
                'value' => 1,
                'label' => $this->l('Yes')),
              array(
                'id' => 'display_off',
                'value' => 0,
                'label' => $this->l('No')),
            ),
          ),
          array(
            'type' => 'switch',
            'label' => $this->l('Linkedin'),
            'name' => 'button_linkedin',
            'is_bool' => true,
            'tab' => 'soc_button_settings',
            'values' => array(
              array(
                'id' => 'display_on',
                'value' => 1,
                'label' => $this->l('Yes')),
              array(
                'id' => 'display_off',
                'value' => 0,
                'label' => $this->l('No')),
            ),
          ),
          array(
            'type' => 'switch',
            'label' => $this->l('Email'),
            'name' => 'button_email',
            'is_bool' => true,
            'tab' => 'soc_button_settings',
            'values' => array(
              array(
                'id' => 'display_on',
                'value' => 1,
                'label' => $this->l('Yes')),
              array(
                'id' => 'display_off',
                'value' => 0,
                'label' => $this->l('No')),
            ),
          ),
          array(
            'type' => 'switch',
            'label' => $this->l('Pinterest'),
            'name' => 'button_pinterest',
            'is_bool' => true,
            'tab' => 'soc_button_settings',
            'values' => array(
              array(
                'id' => 'display_on',
                'value' => 1,
                'label' => $this->l('Yes')),
              array(
                'id' => 'display_off',
                'value' => 0,
                'label' => $this->l('No')),
            ),
          ),
          array(
            'type' => 'switch',
            'label' => $this->l('Pocket'),
            'name' => 'button_pocket',
            'is_bool' => true,
            'tab' => 'soc_button_settings',
            'values' => array(
              array(
                'id' => 'display_on',
                'value' => 1,
                'label' => $this->l('Yes')),
              array(
                'id' => 'display_off',
                'value' => 0,
                'label' => $this->l('No')),
            ),
          ),
          array(
            'type' => 'switch',
            'label' => $this->l('Tumblr'),
            'name' => 'button_tumblr',
            'is_bool' => true,
            'tab' => 'soc_button_settings',
            'values' => array(
              array(
                'id' => 'display_on',
                'value' => 1,
                'label' => $this->l('Yes')),
              array(
                'id' => 'display_off',
                'value' => 0,
                'label' => $this->l('No')),
            ),
          ),
          array(
            'type' => 'switch',
            'label' => $this->l('Reddit'),
            'name' => 'button_reddit',
            'is_bool' => true,
            'tab' => 'soc_button_settings',
            'values' => array(
              array(
                'id' => 'display_on',
                'value' => 1,
                'label' => $this->l('Yes')),
              array(
                'id' => 'display_off',
                'value' => 0,
                'label' => $this->l('No')),
            ),
          ),
          array(
            'type' => 'switch',
            'label' => $this->l('Hackernews'),
            'name' => 'button_hackernews',
            'is_bool' => true,
            'tab' => 'soc_button_settings',
            'values' => array(
              array(
                'id' => 'display_on',
                'value' => 1,
                'label' => $this->l('Yes')),
              array(
                'id' => 'display_off',
                'value' => 0,
                'label' => $this->l('No')
              ),
           ),
        ),
      ),
      'buttons' => array(

      ),
      'submit' => array(
        'title' => $this->l('Save'),
      )
    );
    $helper = new HelperForm();
    $helper->module = $this;
    $helper->name_controller = $this->name;
    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
    $helper->default_form_language = $default_lang;
    $helper->allow_employee_form_lang = $default_lang;
    foreach (Language::getLanguages(false) as $lang) {
      $helper->languages[] = array(
        'id_lang' => $lang['id_lang'],
        'iso_code' => $lang['iso_code'],
        'name' => $lang['name'],
        'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
      );
    }
    $helper->title = $this->displayName;
    $helper->show_toolbar = true;        // false -> remove toolbar
    $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
    $helper->submit_action = 'saveShareButtons';
    $helper->toolbar_btn = array(
      'save' =>
        array(
          'desc' => $this->l('Save'),
          'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
            '&token='.Tools::getAdminTokenLite('AdminModules'),
        ),
      'back' => array(
        'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
        'desc' => $this->l('Back to list')
      )
    );

    $config = Tools::unserialize( Configuration::get( 'GOMAKOIL_SHARE_BUTTONS') );

    if(isset($config)){
      foreach ($config as $key => $value){
        $helper->fields_value[$key] = $value;
      }
    }
    else{
      foreach ($this->_defaultFields as $key => $value){
        $helper->fields_value[$key] = 0;
      }
    }

    $this->_html .= $helper->generateForm($fields_form);
  }



  private function _postProcess()
  {

    if (Tools::isSubmit('saveShareButtons'))
    {

      $config = array(
        'button_facebook'          => Tools::getValue('button_facebook'),
        'button_twitter'           => Tools::getValue('button_twitter'),
        'button_googleplus'        => Tools::getValue('button_googleplus'),
        'button_linkedin'          => Tools::getValue('button_linkedin'),
        'button_email'             => Tools::getValue('button_email'),
        'button_pinterest'         => Tools::getValue('button_pinterest'),
        'button_pocket'            => Tools::getValue('button_pocket'),
        'button_tumblr'            => Tools::getValue('button_tumblr'),
        'button_reddit'            => Tools::getValue('button_reddit'),
        'button_hackernews'        => Tools::getValue('button_hackernews'),
      );

      $message = $this->displayError($this->l('Some error'));;

      $config = serialize($config);
      if( Configuration::updateValue('GOMAKOIL_SHARE_BUTTONS', $config)){
        $message = $this->displayConfirmation($this->l('Data successfully saved!'));
      }



      $this->_html .= $message;
    }
  }


  public function hookDisplayHeader($params)
  {
    $this->context->controller->registerStylesheet('rrssb_socialsharebuttons', 'modules/'.$this->name.'/views/css/rrssb.css', array('media' => 'all', 'priority' => 150));
    $this->context->controller->registerJavascript('rrssb_socialsharebuttons', 'modules/'.$this->name.'/views/js/rrssb.js', array('media' => 'all', 'position' => 'bottom', 'priority' => 150));
    $this->context->controller->registerStylesheet('mpm_socialsharebuttons', 'modules/'.$this->name.'/views/css/mpm_viewproductlist.css', array('media' => 'all', 'priority' => 900));
    $this->context->controller->registerJavascript('mpm_socialsharebuttons', 'modules/'.$this->name.'/views/js/mpm_socialsharebuttons.js', array('position' => 'bottom', 'priority' => 150));
  }

  public function hookDisplayShareButton($params)
  {
    if(!$this->active){
      return false;
    }
    $id_product = Tools::getValue('id_product');


    if(!$id_product){
      return false;
    }

    $buttons = Tools::unserialize( Configuration::get( 'GOMAKOIL_SHARE_BUTTONS') );


    if(!isset($buttons['button_facebook']) || !in_array(1, $buttons)){
      return false;
    }


    $this->smarty->assign($this->getWidgetVariables($params));
    return $this->fetch($this->templateFile);
  }

  public function getWidgetVariables($params)
  {
    $url = false;
    $name = false;
    $description = false;
    $image = false;
    $email = $this->context->cookie->email;
    $id_product = Tools::getValue('id_product');
    $buttons= Tools::unserialize( Configuration::get( 'GOMAKOIL_SHARE_BUTTONS') );


    if($id_product){
      $obj = new Product((int)$id_product, null, (int)Context::getContext()->language->id);
      $present = new ImageRetriever($this->context->link);
      $url = $this->context->link->getProductLink((int)$id_product, null, null, null, (int)Context::getContext()->language->id);
      $images = $present->getImage($obj, $obj->getCoverWs());
      $name = Product::getProductName((int)$id_product, null, (int)Context::getContext()->language->id);
      $description = $obj->description;
    }

    return array(
      'url' => $url,
      'email' => $email,
      'name' => $name,
      'image' => $images,
      'description' => $description,
      'buttons' => $buttons,
    );

  }


}
