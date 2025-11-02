<?php
if (!defined('_PS_VERSION_')) {
    exit;
}
require_once(dirname(__FILE__) . '/classes/homeContacts.php');

class mpm_homecontacts extends Module
{
    private $_shopId;
    private $_langId;

    public function __construct()
    {
        $this->_shopId = Context::getContext()->shop->id;
        $this->_langId = Context::getContext()->language->id;
        $this->name = 'mpm_homecontacts';
        $this->tab = 'front_office_features';
        $this->version = '1.0.1';
        $this->author = 'MyPrestaModules';
        $this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Home contacts');
        $this->description = $this->l('Home contacts block.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    public function install()
    {
        if (!parent::install()
            || !$this->registerHook('actionAdminControllerSetMedia')
            || !$this->registerHook('header')
            || !$this->registerhook('displayContactInfo')
        ) {
            return false;
        }
        $this->_createTab();
        $this->installDb();
        $this->_setDataDb();
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }
        $this->_removeTab();
        $this->uninstallDb();
        return true;
    }

    public function hookActionAdminControllerSetMedia()
    {
        if (Tools::getValue('controller') != 'AdminHomeContacts') {
            return false;
        }

        $this->context->controller->addCSS([
            _PS_MODULE_DIR_.'mpm_homecontacts/views/css/mpm_homecontacts_admin.css'
        ]);
    }

    public function installDb()
    {
        // Table  pages
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'homecontacts';
        Db::getInstance()->execute($sql);
        $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'homecontacts(
				id_homecontacts int(11) unsigned NOT NULL AUTO_INCREMENT,
        hook varchar(255) NOT NULL,
				PRIMARY KEY (`id_homecontacts`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
        Db::getInstance()->execute($sql);
        // Table  pages lang
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'homecontacts_lang';
        Db::getInstance()->execute($sql);
        $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'homecontacts_lang(
				id_homecontacts int(11) unsigned NOT NULL,
				id_lang int(11) unsigned NOT NULL,
				id_shop int(11) unsigned NOT NULL,
				phone varchar(255) NOT NULL,
				phone_description varchar(512) NULL,
				email varchar(255) NOT NULL,
				email_description varchar(512) NULL,
				working_days varchar(255) NOT NULL,
				working_days_description varchar(512) NULL,
				PRIMARY KEY(id_homecontacts, id_shop, id_lang)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
        Db::getInstance()->execute($sql);
    }

    private function uninstallDb()
    {
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'homecontacts';
        Db::getInstance()->execute($sql);
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'homecontacts_lang';
        Db::getInstance()->execute($sql);
    }

    private function _createTab()
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdminHomeContacts';
        $tab->name = [];
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Home Contacts';
        }
        $tab->id_parent = -1;
        $tab->module = $this->name;
        $tab->add();
    }

    private function _removeTab()
    {
        $id_tab = (int)Tab::getIdFromClassName('AdminHomeContacts');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            $tab->delete();
        }
    }

    private function _setDataDb()
    {
        $languages = Language::getLanguages(false);
        $obj = new homeContacts();
        foreach ($languages as $lang) {
            $obj->phone[$lang['id_lang']] = '888 345 6789';
            $obj->phone_description[$lang['id_lang']] = 'Free support line!';
            $obj->email[$lang['id_lang']] = 'demo@demo.com';
            $obj->email_description[$lang['id_lang']] = 'Orders support!';
            $obj->working_days[$lang['id_lang']] = 'Mon - Fri / 8:00 - 18:00';
            $obj->working_days_description[$lang['id_lang']] = 'Working Days / Hours!';
        }
        $obj->hook = 'displayHomeContent5';
        $obj->save();
    }

    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminHomeContacts') . '&updatehomecontacts&id_homecontacts=1');
    }

    public function hookHeader()
    {
        $this->context->controller->registerStylesheet('mpm_homecontacts',
            'modules/' . $this->name . '/views/css/mpm_homecontacts.css', ['media' => 'all', 'priority' => 900]);
    }

    public function hookDisplayContactInfo()
    {
        $obj = new homeContacts();
        $items = $obj->getHomeContacts($this->_langId, $this->_shopId);
        if (isset($items[0]) && $items[0]) {
            $this->smarty->assign($this->getVariables($items[0]));
            return $this->display(__FILE__, 'views/templates/hook/contacts.tpl');
        }
    }

    public function getVariables($items)
    {
        return [
            'id_shop' => $this->_shopId,
            'id_lang' => $this->_langId,
            'items'   => $items,
        ];
    }
}
