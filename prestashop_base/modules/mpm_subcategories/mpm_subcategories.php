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

class Mpm_subcategories extends Module
{
  private $templateFile;

  public function __construct()
  {
    $this->name = 'mpm_subcategories';
    $this->version = '1.0.1';
    $this->author = 'PrestaShop';
    $this->need_instance = 0;
    $this->tab = 'front_office_features';
    $this->bootstrap = true;
    parent::__construct();

    $this->displayName = $this->l('Category info');
    $this->description = $this->l('Displays a subcategories on category page.');

    $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);

    $this->templateFile = 'module:mpm_subcategories/views/templates/hook/subcategories.tpl';

  }

  public function install()
  {
    Configuration::updateValue('GOMAKOIL_SUBCATEGORIES_SLIDER', 1);
    Configuration::updateValue('GOMAKOIL_CATEGORY_IMAGE', 1);
    Configuration::updateValue('GOMAKOIL_CATEGORY_DESCRIPTION', 1);

    return (parent::install()
      && $this->registerHook('displayHeader')
      && $this->registerHook('displaySubcategories')
    );
  }


  public function uninstall()
  {
    return parent::uninstall();
  }

  public function hookDisplayHeader($params)
  {
    if($this->context->controller->php_self == 'category'){
      $this->context->controller->registerStylesheet('mpm_subcategories', 'modules/'.$this->name.'/views/css/mpm_subcategories.css', array('media' => 'all', 'priority' => 900));
      $this->context->controller->registerJavascript('mpm_subcategories', 'modules/'.$this->name.'/views/js/mpm_subcategories.js', array('position' => 'bottom', 'priority' => 150));
    }
  }

  public function getContent()
  {

    $output = '';
    $errors = '';
    if (Tools::isSubmit('submitSubcategories'))
    {
      $active = (int)(Tools::getValue('GOMAKOIL_SUBCATEGORIES_SLIDER'));
      $image = (int)(Tools::getValue('GOMAKOIL_CATEGORY_IMAGE'));
      $description = (int)(Tools::getValue('GOMAKOIL_CATEGORY_DESCRIPTION'));

      Configuration::updateValue('GOMAKOIL_SUBCATEGORIES_SLIDER', $active);
      Configuration::updateValue('GOMAKOIL_CATEGORY_IMAGE', $image);
      Configuration::updateValue('GOMAKOIL_CATEGORY_DESCRIPTION', $description);

      if (isset($errors) AND $errors){
        $output .= $this->displayError($errors);
      }
      else{
        $output .= $this->displayConfirmation($this->l('Settings updated.'));
      }
    }
    return $output.$this->renderForm();
  }

  public function renderForm()
  {

    $fields_form = array(
      'form' => array(
        'legend' => array(
          'title' => $this->l('Settings'),
          'icon' => 'icon-cogs'
        ),
        'input' => array(
          array(
            'type' => 'switch',
            'label' => $this->l('Display subcategories slider'),
            'name' => 'GOMAKOIL_SUBCATEGORIES_SLIDER',
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
        ),
        'submit' => array(
          'title' => $this->l('Save'),
        )
      ),
    );


    $helper = new HelperForm();
    $helper->show_toolbar = false;
    $helper->table =  $this->table;
    $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
    $helper->default_form_language = $lang->id;
    $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
    $helper->identifier = $this->identifier;
    $helper->submit_action = 'submitSubcategories';
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
    return array(
      'GOMAKOIL_SUBCATEGORIES_SLIDER' => Tools::getValue('GOMAKOIL_SUBCATEGORIES_SLIDER', Configuration::get('GOMAKOIL_SUBCATEGORIES_SLIDER')),
      'GOMAKOIL_CATEGORY_IMAGE'       => Tools::getValue('GOMAKOIL_CATEGORY_IMAGE', Configuration::get('GOMAKOIL_CATEGORY_IMAGE')),
      'GOMAKOIL_CATEGORY_DESCRIPTION' => Tools::getValue('GOMAKOIL_CATEGORY_DESCRIPTION', Configuration::get('GOMAKOIL_CATEGORY_DESCRIPTION')),
    );
  }



  public function hookDisplaySubcategories($params)
  {

    $currentCategory = array();
    $id_category = Tools::getValue('id_category');

    if($this->context->controller->php_self != 'category' || !$id_category){
      return false;
    }

    $obj = new Category($id_category, Context::getContext()->language->id);
    $categories = $obj->getSubCategories(Context::getContext()->language->id);
    $obj_img = new ImageRetriever( Context::getContext()->link);


    $currentCategory['description'] = $obj->description;
    $currentCategory['name'] = $obj->name;
    $currentCategory['img'] = $obj_img->getImage($obj, $obj->id_image);

    foreach ($categories as $key => $category){
      $cat = new Category($category['id_category'], Context::getContext()->language->id);
      $categories[$key]['images'] = $obj_img->getImage($cat, $cat->id_image);
      $categories[$key]['link'] = Context::getContext()->link->getCategoryLink($category['id_category'],$category['link_rewrite'],Context::getContext()->language->id );
    }


    $active = Configuration::get('GOMAKOIL_SUBCATEGORIES_SLIDER');
    $image = Configuration::get('GOMAKOIL_CATEGORY_IMAGE');
    $description = Configuration::get('GOMAKOIL_CATEGORY_DESCRIPTION');

    $this->smarty->assign(
      array(
        'active'        => $active,
        'image'         => $image,
        'description'   => $description,
        'subcategories' => $categories,
        'cat'           => $currentCategory,
      )
    );

    return $this->fetch($this->templateFile);
  }


}
