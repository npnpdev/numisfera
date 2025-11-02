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


class mpm_themeconfigurator extends Module
{
  private $templateFile;
  private $_imgDir;

  public function __construct()
  {
    $this->name = 'mpm_themeconfigurator';
    $this->version = '1.0.1';
    $this->author = 'PrestaShop';
    $this->need_instance = 0;
    $this->tab = 'front_office_features';
    $this->bootstrap = true;
    parent::__construct();

    $this->displayName = $this->l('Theme configurator');
    $this->description = $this->l('The customization tool allows you to make color and font changes in your theme.');

    $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);

    $this->templateFile = 'module:mpm_themeconfigurator/views/templates/hook/configurator.tpl';
    $this->_imgDir = _PS_MODULE_DIR_ . 'mpm_footer/views/img/';
  }

  public function install()
  {


    if (!parent::install()
      || !Configuration::updateValue('FONTS_CONFIGURATOR_ACTIVE', '')
      || !Configuration::updateValue('THEME_BACKGROUND_COLOR', '#d19e65')
      || !Configuration::updateValue('THEME_BACKGROUND_COLOR_HOVER', '#ffffff')
      || !Configuration::updateValue('THEME_BACKGROUND_PAGE', '#ffffff')
      || !Configuration::updateValue('BACKGROUND_COLOR_FOOTER', '#000000')
      || !Configuration::updateValue('COLOR_FOOTER', '#ffffff')
      || !Configuration::updateValue('COLOR_HOVER_FOOTER', '#d19e65')
      || !Configuration::updateValue('GOMAKOIL_PRODUCT_ZOOM', 1)


      || !$this->registerHook('displayHeader')
	  || !$this->registerHook('actionAdminControllerSetMedia')
    )
      return false;

    return true;
  }


  public function uninstall()
  {
    if ( !parent::uninstall()

      || !Configuration::deleteByName('FONTS_CONFIGURATOR_ACTIVE')
      || !Configuration::deleteByName('THEME_BACKGROUND_COLOR')
      || !Configuration::deleteByName('THEME_BACKGROUND_COLOR_HOVER')
      || !Configuration::deleteByName('THEME_BACKGROUND_PAGE')
    )
      return false;

    return true;
  }

  public function getContent()
  {
       $this->context->controller->addCss($this->_path.'views/css/mpm_themeconfigurator_admin.css', 'all');
    return $this->postProcess().$this->renderForm();
  }
  public function renderForm()
  {
    $image = $this->_imgDir.'background.png';
    $image_url = ImageManager::thumbnail($image, 'background.png', 350, 'png', true, true);
    $image_size = file_exists($image) ? filesize($image) / 1000 : false;
    $fonts = array(

      array(
        'name'  => 'Choose a font',
        'id'  => 'font',
      ),
      array(
        'name'  => 'Open Sans',
        'id'  => 'font1',
      ),
      array(
        'name'  => 'Josefin Slab',
        'id'  => 'font2',
      ),
      array(
        'name'  => 'Arvo',
        'id'  => 'font3',
      ),
      array(
        'name'  => 'Lato',
        'id'  => 'font4',
      ),
      array(
        'name'  => 'Volkorn',
        'id'  => 'font5',
      ),
      array(
        'name'  => 'Abril Fatface',
        'id'  => 'font6',
      ),
      array(
        'name'  => 'Ubuntu',
        'id'  => 'font7',
      ),
      array(
        'name'  => 'PT Sans',
        'id'  => 'font8',
      ),
      array(
        'name'  => 'Old Standard TT',
        'id'  => 'font9',
      ),
      array(
        'name'  => 'Droid Sans',
        'id'  => 'font10',
      ),
      array(
        'name'  => 'PT Sans Narrow',
        'id'  => 'font11',
      ),
      array(
        'name'  => 'Arial',
        'id'  => 'font12',
      ),
    );

    $fields_form = array(
      'form' => array(
        'legend' => array(
          'title' =>$this->l('Settings'),
          'icon' => 'icon-cogs'
        ),
        'input' => array(

          array(
            'type' => 'color',
            'label' => 'Main background color',
            'name' => 'THEME_BACKGROUND_COLOR',
            'desc' => $this->l('Default: #d19e65'),
          ),
          array(
            'type' => 'color',
            'label' => 'Main background color on hover',
            'name' => 'THEME_BACKGROUND_COLOR_HOVER',
            'desc' => $this->l('Default: #ffffff'),
          ),
          array(
            'type' => 'color',
            'label' => 'Background page',
            'name' => 'THEME_BACKGROUND_PAGE',
            'desc' => $this->l('Default: #ffffff'),
          ),
          array(
            'type' => 'color',
            'label' => 'Background color footer',
            'name' => 'BACKGROUND_COLOR_FOOTER',
            'desc' => $this->l('Default: #000000'),
          ),
          array(
            'type' => 'file',
            'label' => $this->l('Background image footer'),
            'name' => 'file',
            'display_image' => true,
            'image' => $image_url ? $image_url : false,
            'size' => $image_size,
            'delete_url' => $this->context->link->getAdminLink('AdminModules', true).'&configure='.$this->name.'&module_name='.$this->name.'&deleteImageFooter=1',
          ),
          array(
            'type' => 'color',
            'label' => 'Color text in footer',
            'name' => 'COLOR_FOOTER',
            'desc' => $this->l('Default: #ffffff'),
          ),
          array(
            'type' => 'color',
            'label' => 'Color hover text in footer',
            'name' => 'COLOR_HOVER_FOOTER',
            'desc' => $this->l('Default: #d19e65'),
          ),
          array(
            'type' => 'html',
            'label' => $this->l('Theme font'),
            'name' => 'html_data',
            'form_group_class'=> 'html_data_settings default_settings',
            'html_content' => '',
          ),
          array(
            'type' => 'select',
            'label' => $this->l('Font'),
            'name' => 'FONTS_CONFIGURATOR_ACTIVE',
            'options' => array(
              'query' => $fonts,
              'name' => 'name',
              'id' => 'id'
            ),
            'desc' => $this->l('Default: Lato'),
          ),
          array(
            'type' => 'html',
            'label' => $this->l('Category settings'),
            'name' => 'html_data',
            'form_group_class'=> 'html_data_settings default_settings',
            'html_content' => '',
          ),
          array(
            'type' => 'switch',
            'label' => $this->l('Display category image'),
            'name' => 'GOMAKOIL_CATEGORY_IMAGE',
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
            ),
          ),
          array(
            'type' => 'switch',
            'label' => $this->l('Display category description'),
            'name' => 'GOMAKOIL_CATEGORY_DESCRIPTION',
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
            ),
          ),
          array(
            'type' => 'switch',
            'label' => $this->l('Display subcategories in category page'),
            'name' => 'GOMAKOIL_SUBCATEGORIES_SLIDER',
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
            'label' => $this->l('Active zoom images product '),
            'name' => 'GOMAKOIL_PRODUCT_ZOOM',
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
    $helper->submit_action = 'submitConfigurator';
    $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->tpl_vars = array(
      'uri' => $this->getPathUri(),
      'fields_value' => $this->getConfigFieldsValues(),
      'languages' => $this->context->controller->getLanguages(),
      'id_language' => $this->context->language->id
    );

    return $helper->generateForm(array($fields_form));
  }
  
  public function hookActionAdminControllerSetMedia()
  {
	$this->context->controller->addJS('https://myprestamodules.com/modules/relatedmodules/js/modules.js?v='.date('Y.m.d'));  
  }

  public function postProcess()
  {
    if (Tools::isSubmit('submitConfigurator')) {
      $this->uploadImages('background');

      Configuration::updateValue('FONTS_CONFIGURATOR_ACTIVE', Tools::getValue('FONTS_CONFIGURATOR_ACTIVE'));
      Configuration::updateValue('THEME_BACKGROUND_COLOR', Tools::getValue('THEME_BACKGROUND_COLOR'));
      Configuration::updateValue('THEME_BACKGROUND_COLOR_HOVER', Tools::getValue('THEME_BACKGROUND_COLOR_HOVER'));
      Configuration::updateValue('THEME_BACKGROUND_PAGE', Tools::getValue('THEME_BACKGROUND_PAGE'));
      Configuration::updateValue('BACKGROUND_COLOR_FOOTER', Tools::getValue('BACKGROUND_COLOR_FOOTER'));
      Configuration::updateValue('COLOR_FOOTER', Tools::getValue('COLOR_FOOTER'));
      Configuration::updateValue('COLOR_HOVER_FOOTER', Tools::getValue('COLOR_HOVER_FOOTER'));
      Configuration::updateValue('GOMAKOIL_SUBCATEGORIES_SLIDER', (int)Tools::getValue('GOMAKOIL_SUBCATEGORIES_SLIDER'));
      Configuration::updateValue('GOMAKOIL_PRODUCT_ZOOM', (int)Tools::getValue('GOMAKOIL_PRODUCT_ZOOM'));


      Configuration::updateValue('GOMAKOIL_CATEGORY_IMAGE', (int)Tools::getValue('GOMAKOIL_CATEGORY_IMAGE'));
      Configuration::updateValue('GOMAKOIL_CATEGORY_DESCRIPTION', (int)Tools::getValue('GOMAKOIL_CATEGORY_DESCRIPTION'));



      return $this->displayConfirmation($this->l('The settings have been updated.'));
    }

    if (Tools::getValue('deleteImageFooter')) {

      unlink($this->_imgDir.'background.png');

      return $this->displayConfirmation($this->l('The settings have been updated.'));
    }


    return '';
  }

  public function getConfigFieldsValues()
  {
    return array(
      'FONTS_CONFIGURATOR_ACTIVE'     => Tools::getValue('FONTS_CONFIGURATOR_ACTIVE', Configuration::get('FONTS_CONFIGURATOR_ACTIVE')),
      'THEME_BACKGROUND_COLOR'        => Tools::getValue('THEME_BACKGROUND_COLOR', Configuration::get('THEME_BACKGROUND_COLOR')),
      'THEME_BACKGROUND_COLOR_HOVER'  => Tools::getValue('THEME_BACKGROUND_COLOR_HOVER', Configuration::get('THEME_BACKGROUND_COLOR_HOVER')),
      'THEME_BACKGROUND_PAGE'         => Tools::getValue('THEME_BACKGROUND_PAGE', Configuration::get('THEME_BACKGROUND_PAGE')),
      'BACKGROUND_COLOR_FOOTER'       => Tools::getValue('BACKGROUND_COLOR_FOOTER', Configuration::get('BACKGROUND_COLOR_FOOTER')),
      'COLOR_FOOTER'                  => Tools::getValue('COLOR_FOOTER', Configuration::get('COLOR_FOOTER')),
      'COLOR_HOVER_FOOTER'            => Tools::getValue('COLOR_HOVER_FOOTER', Configuration::get('COLOR_HOVER_FOOTER')),
      'GOMAKOIL_SUBCATEGORIES_SLIDER' => Tools::getValue('GOMAKOIL_SUBCATEGORIES_SLIDER', Configuration::get('GOMAKOIL_SUBCATEGORIES_SLIDER')),
      'GOMAKOIL_PRODUCT_ZOOM'         => Tools::getValue('GOMAKOIL_PRODUCT_ZOOM', Configuration::get('GOMAKOIL_PRODUCT_ZOOM')),
      'GOMAKOIL_CATEGORY_IMAGE'       => Tools::getValue('GOMAKOIL_CATEGORY_IMAGE', Configuration::get('GOMAKOIL_CATEGORY_IMAGE')),
      'GOMAKOIL_CATEGORY_DESCRIPTION' => Tools::getValue('GOMAKOIL_CATEGORY_DESCRIPTION', Configuration::get('GOMAKOIL_CATEGORY_DESCRIPTION')),

    );
  }


  protected function uploadImages($id)
  {

      if (isset($_FILES['file']) && isset($_FILES['file']['tmp_name']) && !empty($_FILES['file']['tmp_name'])) {
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


  public function hookDisplayHeader($params)
  {




      if (Configuration::get('FONTS_CONFIGURATOR_ACTIVE') != ''){
        $this->context->controller->registerStylesheet('mpm_themeconfigurator_font_active', 'modules/'.$this->name.'/views/css/'.Configuration::get('FONTS_CONFIGURATOR_ACTIVE').'.css', array('media' => 'all', 'priority' => 900));
      }

      if (Configuration::get('THEME_BACKGROUND_COLOR') == '#d19e65' && Configuration::get('THEME_BACKGROUND_COLOR_HOVER') == '#ffffff' && Configuration::get('THEME_BACKGROUND_PAGE') == '#ffffff')
      {
        return false;
      }


      if (Configuration::get('THEME_BACKGROUND_COLOR') != '' && Configuration::get('THEME_BACKGROUND_COLOR_HOVER') != '' ){
        $this->smarty->assign(
          array(
            'background' => Configuration::get('THEME_BACKGROUND_COLOR'),
            'background_hover' => Configuration::get('THEME_BACKGROUND_COLOR_HOVER'),
            'page' => Configuration::get('THEME_BACKGROUND_PAGE'),

          ));
        return $this->display(__FILE__, 'configurator.tpl');
      }

  }


}
