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

  class mpm_solutions extends Module
  {
    public function __construct()
    {
      $this->name = 'mpm_solutions';
      $this->version = '1.0.0';
      $this->author = 'PrestaShop';
      $this->need_instance = 0;
      $this->tab = 'front_office_features';
      $this->bootstrap = true;
      parent::__construct();

      $this->displayName = $this->l('MyPrestaModules Solutions');
      $this->description = $this->l('MyPrestaModules Solutions', array());

      $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
    }

    public function install()
    {
      if (!parent::install()) {
        return false;
      }
      $this->_createTab();
      $this->registerHook('actionAdminControllerSetMedia');
      return true;
    }

    public function uninstall()
    {
      $this->_removeTab();
      return parent::uninstall();
    }

    private function _createTab()
    {
      $tabs = array(
        array(
          'controller' => 'AdminMpmSolutions',
          'parent' => $this->getIdTabFromClassName('AdminCatalog'),
          'name' => 'Product Catalog Import'
        ),
      );

      foreach( $tabs as $tab ){
        if (!$this->existsTab($tab['controller'])) {
          $tabIsAdded = $this->addTab($tab['name'], $tab['controller'], $tab['parent']);
          if (!$tabIsAdded) {
            return false;
          }
        }
      }
    }

    private function _removeTab()
    {
      $id_tabs = array(
        $this->getIdTabFromClassName('AdminMpmSolutions'),
      );

      foreach( $id_tabs as $id_tab ){
        if ($id_tab)
        {
          $tab = new Tab($id_tab);
          $tab->delete();
        }
      }
    }

    public function getIdTabFromClassName($tabName)
    {
      $sql = 'SELECT id_tab FROM ' . _DB_PREFIX_ . 'tab WHERE class_name="' . pSQL($tabName) . '"';
      $tab = Db::getInstance()->getRow($sql);
      if( $tab ){
        return (int)$tab['id_tab'];
      }

      return false;
    }

    public function existsTab($tabClass)
    {
      $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
		SELECT id_tab AS id
		FROM `' . _DB_PREFIX_ . 'tab` t
		WHERE LOWER(t.`class_name`) = \'' . pSQL($tabClass) . '\'');
      $count = count($result);
      if ($count == 0) {
        return false;
      }

      return true;
    }

    public function addTab($tabName, $tabClass, $id_parent, $icon = false)
    {
      $tab = new Tab();
      $langs = Language::getLanguages();
      foreach ($langs as $lang) {
        $tab->name[$lang['id_lang']] = $tabName;
      }
      $tab->class_name = $tabClass;
      $tab->module = $this->name;
      $tab->id_parent = $id_parent;
      if ($icon) {
        $tab->icon = $icon;
      }
      $save = $tab->save();
      if (!$save) {
        return false;
      }

      return true;
    }

    public function hookActionAdminControllerSetMedia()
    {
      $this->context->controller->addCSS([
        _PS_MODULE_DIR_ . 'mpm_solutions/views/css/style.css',
      ]);
      if (Tools::getValue('controller') == 'AdminMpmSolutions') {
        $this->context->controller->addCSS([
          _PS_MODULE_DIR_ . 'mpm_solutions/views/css/mpm_solutions.css',
        ]);
        $this->context->controller->addCSS('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;800&display=swap');
      }

    }

  }