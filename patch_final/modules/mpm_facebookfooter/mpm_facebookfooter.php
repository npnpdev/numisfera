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

if (!defined('_PS_VERSION_'))
	exit;


class mpm_facebookfooter extends Module
{

    public $templateFile;
    public $fields_form;

	public function __construct()
	{
		$this->name = 'mpm_facebookfooter';
		$this->tab = 'front_office_features';
		$this->version = '1.0.1';
		$this->author = 'MyPrestaModules';

		$this->bootstrap = true;
		parent::__construct();
		$this->displayName = $this->l('Facebook block in footer');
		$this->description = $this->l('Displays a block for subscribing to your Facebook Page.');

    $this->templateFile = 'module:mpm_facebookfooter/views/templates/hook/blockfacebookfooter.tpl';
	}


	public function install()
	{

    if (!parent::install()
      || !$this->registerHook('footer')
      || !$this->registerHook('header')

      || !Configuration::updateValue('GOMAKOIL_FACEBOOK_URL', 'https://www.facebook.com/prestashop')

    )
      return false;

    return true;

	}

	public function uninstall()
	{
		return Configuration::deleteByName('GOMAKOIL_FACEBOOK_URL') && parent::uninstall();
	}

	public function getContent()
	{
		$output = '';

		if (Tools::isSubmit('submitModule'))
		{
			Configuration::updateValue('GOMAKOIL_FACEBOOK_URL', Tools::getValue('GOMAKOIL_FACEBOOK_URL'));
			$output .= $this->displayConfirmation($this->l('Configuration updated'));
			$this->_clearCache('blockfacebookfooter.tpl');
		}

		return $output.$this->renderForm();
	}


  public function hookDisplayHeader($params)
  {
    $this->context->controller->registerStylesheet('blockfacebookfooter', 'modules/mpm_facebookfooter/views/css/blockfacebookfooter.css',  array('media' => 'all', 'priority' => 900) );
    $this->context->controller->registerJavascript( 'blockfacebookfooter',  'modules/mpm_facebookfooter/views/js/blockfacebookfooter.css', array('position' => 'bottom', 'priority' => 100) );

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
						'type' => 'text',
						'label' => $this->l('Facebook link (full URL is required)'),
						'name' => 'GOMAKOIL_FACEBOOK_URL',
					),
				),
				'submit' => array(
					'title' => $this->l('Save')
				)
			),
		);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitModule';
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
			'GOMAKOIL_FACEBOOK_URL' => Tools::getValue('GOMAKOIL_FACEBOOK_URL', Configuration::get('GOMAKOIL_FACEBOOK_URL')),
		);
	}



  public function hookDisplayFooter($params)
  {
    $this->smarty->assign($this->getWidgetVariables());

    return $this->fetch($this->templateFile);

  }

  public function getWidgetVariables()
  {

    $url = Configuration::get('GOMAKOIL_FACEBOOK_URL');
    if (!strstr($url, 'facebook.com')){
      $url = 'https://www.facebook.com/'.$url;
    }

    return array(
      'url'  => $url,

    );
  }


}
