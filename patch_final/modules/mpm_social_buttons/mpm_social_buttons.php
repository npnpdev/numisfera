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


class Mpm_social_buttons extends Module
{
  private $templateFile;

  public function __construct()
  {
    $this->name = 'mpm_social_buttons';
    $this->version = '1.0.1';
    $this->author = 'PrestaShop';
    $this->need_instance = 0;
    $this->tab = 'front_office_features';
    $this->bootstrap = true;
    parent::__construct();

    $this->displayName = $this->l('Social_buttons in footer');
    $this->description = $this->l('Social_buttons in footer.');

    $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);

    $this->templateFile = 'module:mpm_social_buttons/views/templates/hook/buttons.tpl';

  }

  public function install()
  {

    Configuration::updateValue('FACEBOOK_LINK', 'https://www.facebook.com/prestashop');
    Configuration::updateValue('TWITTER_LINK', 'https://twitter.com/PrestaShop');
    Configuration::updateValue('RSS_LINK', '');
    Configuration::updateValue('YOUTUBE_LINK', '');
    Configuration::updateValue('GOOGLE_LINK', 'https://plus.google.com/+prestashop');
    Configuration::updateValue('PINTEREST_LINK', 'https://www.pinterest.com/prestashop/');
    Configuration::updateValue('VIMEO_LINK', '');
    Configuration::updateValue('INSTAGRAM_LINK', 'https://www.instagram.com/prestashop/');


    return (parent::install() &&
      $this->registerHook('displayHeader') &&
       $this->registerHook('displayFooter')
    );
  }


  public function uninstall()
  {

    Configuration::deleteByName('FACEBOOK_LINK');
    Configuration::deleteByName('TWITTER_LINK');
    Configuration::deleteByName('RSS_LINK');
    Configuration::deleteByName('YOUTUBE_LINK');
    Configuration::deleteByName('GOOGLE_LINK');
    Configuration::deleteByName('PINTEREST_LINK');
    Configuration::deleteByName('VIMEO_LINK');
    Configuration::deleteByName('INSTAGRAM_LINK');


    return parent::uninstall();
  }

  public function postProcess()
  {
    if (Tools::isSubmit('submitSocialButtons')) {

      Configuration::updateValue('FACEBOOK_LINK', Tools::getValue('FACEBOOK_LINK'));
      Configuration::updateValue('TWITTER_LINK', Tools::getValue('TWITTER_LINK'));
      Configuration::updateValue('RSS_LINK', Tools::getValue('RSS_LINK'));
      Configuration::updateValue('YOUTUBE_LINK', Tools::getValue('YOUTUBE_LINK'));
      Configuration::updateValue('GOOGLE_LINK', Tools::getValue('GOOGLE_LINK'));
      Configuration::updateValue('PINTEREST_LINK', Tools::getValue('PINTEREST_LINK'));
      Configuration::updateValue('VIMEO_LINK', Tools::getValue('VIMEO_LINK'));
      Configuration::updateValue('INSTAGRAM_LINK', Tools::getValue('INSTAGRAM_LINK'));

      return $this->displayConfirmation($this->l('The settings have been updated.'));
    }

    return '';
  }

  public function getContent()
  {
    return $this->postProcess().$this->renderForm();
  }

  public function renderForm()
  {
    $fields_form = array(
      'form' => array(
        'legend' => array(
          'title' =>$this->l('Settings'),
          'icon' => 'icon-cogs'
        ),
        'input' => array(

          array(
            'type' => 'text',
            'label' => $this->l('Facebook URL'),
            'name' => 'FACEBOOK_LINK',
            'desc' => $this->l('Your Facebook fan page.')
          ),
          array(
            'type' => 'text',
            'label' => $this->l('Twitter URL'),
            'name' => 'TWITTER_LINK',
            'desc' => $this->l('Your official Twitter account.')
          ),
          array(
            'type' => 'text',
            'label' => $this->l('RSS URL'),
            'name' => 'RSS_LINK',
            'desc' => $this->l('The RSS feed of your choice (your blog, your store, etc.).')
          ),
          array(
            'type' => 'text',
            'label' => $this->l('YouTube URL'),
            'name' => 'YOUTUBE_LINK',
            'desc' => $this->l('Your official YouTube account.')
          ),
          array(
            'type' => 'text',
            'label' => $this->l('Google+ URL:'),
            'name' => 'GOOGLE_LINK',
            'desc' => $this->l('Your official Google+ page.')
          ),
          array(
            'type' => 'text',
            'label' => $this->l('Pinterest URL:'),
            'name' => 'PINTEREST_LINK',
            'desc' => $this->l('Your official Pinterest account.')
          ),
          array(
            'type' => 'text',
            'label' => $this->l('Vimeo URL:'),
            'name' => 'VIMEO_LINK',
            'desc' => $this->l('Your official Vimeo account.')
          ),
          array(
            'type' => 'text',
            'label' => $this->l('Instagram URL:'),
            'name' => 'INSTAGRAM_LINK',
            'desc' => $this->l('Your official Instagram account.')
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
    $helper->submit_action = 'submitSocialButtons';
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

  public function getConfigFieldsValues()
  {
    return array(
      'FACEBOOK_LINK' => Tools::getValue('FACEBOOK_LINK', Configuration::get('FACEBOOK_LINK')),
      'TWITTER_LINK' => Tools::getValue('TWITTER_LINK', Configuration::get('TWITTER_LINK')),
      'RSS_LINK' => Tools::getValue('RSS_LINK', Configuration::get('RSS_LINK')),
      'YOUTUBE_LINK' => Tools::getValue('YOUTUBE_LINK', Configuration::get('YOUTUBE_LINK')),
      'GOOGLE_LINK' => Tools::getValue('GOOGLE_LINK', Configuration::get('GOOGLE_LINK')),
      'PINTEREST_LINK' => Tools::getValue('PINTEREST_LINK', Configuration::get('PINTEREST_LINK')),
      'VIMEO_LINK' => Tools::getValue('VIMEO_LINK', Configuration::get('VIMEO_LINK')),
      'INSTAGRAM_LINK' => Tools::getValue('INSTAGRAM_LINK', Configuration::get('INSTAGRAM_LINK')),
    );
  }


  public function hookDisplayHeader($params)
  {
    $this->context->controller->registerStylesheet('mpm_social_buttons', 'modules/mpm_social_buttons/views/css/buttons.css', array('media' => 'all', 'priority' => 900));
  }

  public function hookDisplayFooter($params)
  {
    $this->smarty->assign($this->getWidgetVariables($params));
    return $this->fetch($this->templateFile);
  }

  public function getWidgetVariables($params)
  {
    return array(
      'facebook'  => Configuration::get('FACEBOOK_LINK'),
      'twitter'   => Configuration::get('TWITTER_LINK'),
      'rss'       => Configuration::get('RSS_LINK'),
      'youtube'   => Configuration::get('YOUTUBE_LINK'),
      'google'    => Configuration::get('GOOGLE_LINK'),
      'pinterest' => Configuration::get('PINTEREST_LINK'),
      'vimeo'     => Configuration::get('VIMEO_LINK'),
      'instagram' => Configuration::get('INSTAGRAM_LINK'),
    );
  }




}
