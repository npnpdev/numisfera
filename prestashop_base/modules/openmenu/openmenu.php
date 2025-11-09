<?php
/**
* History:
*
* 1.0.0    First version
*
*  @author    Vincent MASSON <contact@coeos.pro>
*  @copyright Vincent MASSON <www.coeos.pro>
*  @license   https://www.coeos.pro/fr/content/3-conditions-generales-de-ventes
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class OpenMenu extends Module
{
    /* @var boolean error */
    protected $error = false;

    /**
    * Module constructor
    */

    public function __construct()
    {
        $this->name = 'openmenu';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'coeos.pro';
        $this->need_instance = 0;
        $this->come_from = 'coeos.pro';
        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Open Menu');
        $this->description = $this->l('This module allows you to open the menu on hover, without compulsory click!');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }
    public function install()
    {
        return parent::install() && $this->registerHook('displayBackOfficeHeader');
    }
    public function uninstall()
    {
        return parent::uninstall();
    }
    public function hookdisplayBackOfficeHeader()
    {
        $this->context->controller->addJS(($this->_path).'views/js/menu.js');
        $this->context->controller->addCSS(($this->_path).'views/css/menu.css');
    }
    public function getContent()
    {
        $ic = Language::getIsoById((int)Configuration::get('PS_LANG_DEFAULT'));
        if ($this->come_from == 'addons') {
            $iso_code = (in_array($ic, array('en', 'fr', 'es', 'de', 'it', 'nl', 'pl', 'pt', 'ru')))? $ic : 'en';
        } else {
            $iso_code = (in_array($ic, array('en', 'fr', 'es', 'de', 'it')))? $ic : 'en';
        }
        $this->smarty->assign(array(
            'ps_version' => _PS_VERSION_,
            'display_name' => $this->displayName,
            'author' => $this->author,
            'version' => $this->version,
            'shop_name' => $this->context->shop->name,
            'path' => $this->_path,
            'tpl_dir' => '',
            'iso_code' => $iso_code,
            'come_from' => $this->come_from,
            'name' => $this->name,
            ));
        $this->_html = $this->display(__FILE__, 'views/templates/admin/prestui/'.$this->name.'.tpl');
        $this->_html .= $this->display(__FILE__, 'views/templates/admin/prestui/ps-alert.tpl');
        $this->_html .= $this->display(__FILE__, 'views/templates/admin/prestui/ps-form.tpl');
        $this->_html .= $this->display(__FILE__, 'views/templates/admin/prestui/ps-panel.tpl');
        $this->_html .= $this->display(__FILE__, 'views/templates/admin/prestui/ps-table.tpl');
        $this->_html .= $this->display(__FILE__, 'views/templates/admin/prestui/ps-tabs.tpl');
        $this->_html .= $this->display(__FILE__, 'views/templates/admin/prestui/ps-tags.tpl');
        $this->context->controller->addJS(($this->_path).'views/js/riot.min.js');
        $this->context->controller->addJS(($this->_path).'views/js/riot_compiler.min.js');
        return $this->_html;
    }
}
