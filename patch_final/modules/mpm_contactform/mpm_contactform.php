<?php
/**
 * Created by PhpStorm.
 * User: maskc_000
 * Date: 08.11.13
 * Time: 10:59
 */
require_once(dirname(__FILE__) . '/classes/ContactFormClass.php');

class mpm_contactform extends Module
{
    private $_contactFormClass;
    private $_idShop;
    private $_idLang;

    public function __construct()
    {
        $this->name = 'mpm_contactform';
        $this->tab = 'front_office_features';
        $this->version = '2.2.1';
        $this->author = 'MyPrestaModules';
        $this->need_instance = 0;
        $this->module_key = "c0be709c76df8dcc513130a05a6f9c7f";
        parent::__construct(); // The parent construct is required for translations
        $this->displayName = $this->l('Extended Contact Form');
        $this->description = $this->l('“Extended Contact Form” allows you to create contact forms that work without page reload.');
        $this->_contactFormClass = new ContactFormClass();
        $this->_idShop = Context::getContext()->shop->id;
        $this->_idLang = Context::getContext()->language->id;
    }

    public function install()
    {
        if (!parent::install()
            || !$this->registerHook('actionAdminControllerSetMedia')
            || !$this->registerHook('header')
            || !$this->registerhook('displayOverrideTemplate')
        ) {
            return false;
        }
        $this->_createTab('AdminContactForm', 'Contact Form');
        $this->installDb();
        $this->setInDb();
        return true;
    }

    public function uninstall()
    {
        /* Deletes Module */
        if (parent::uninstall()) {
            return true;
        }
        $this->_removeTab('AdminContactForm');
        $this->uninstallDb();
        return false;
    }

    public function hookActionAdminControllerSetMedia()
    {
        if (Tools::getValue('controller') != 'AdminContactForm') {
            return false;
        }

        $this->context->controller->addCSS([
            _PS_MODULE_DIR_.'mpm_contactform/views/css/contactform_admin.css'
        ]);
    }

    private function _createTab($class_name, $name)
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = $class_name;
        $tab->name = [];
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $name;
        }
        $tab->id_parent = -1;
        $tab->module = $this->name;
        $tab->add();
    }

    private function _removeTab($class_name)
    {
        $id_tab = (int)Tab::getIdFromClassName($class_name);
        if ($id_tab) {
            $tab = new Tab($id_tab);
            $tab->delete();
        }
    }

    public function installDb()
    {
        // Table  pages
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'contactform';
        Db::getInstance()->execute($sql);
        $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'contactform(
				id_contactform int(11) unsigned NOT NULL AUTO_INCREMENT,
				email varchar(255) NULL,
				background varchar(255) NULL,
				color varchar(255) NULL,
				block_description int(11) NULL,
				block_form int(11) NULL,
				block_image int(11) NULL,
				block_maps int(11) NULL,
                position_description varchar(255) NULL,
                position_form varchar(255) NULL,
                position_image varchar(255) NULL,
                position_maps varchar(255) NULL,
                name_field int(11) NULL,
                email_field int(11) NULL,
                phone_field int(11) NULL,
                subject_field int(11) NULL,
                captcha_field int(11) NULL,
                attach_field int(11) NULL,
                name_field_required int(11) NULL,
                email_field_required int(11) NULL,
                phone_field_required int(11) NULL,
                subject_field_required int(11) NULL,
                background_button varchar(255) NULL,
                background_button_hover varchar(255) NULL,
                color_button varchar(255) NULL,
                maps_code varchar(512) NULL,
                width_maps int(11) NULL,
                height_maps int(11) NULL,
                width_description int(11) NULL,
                width_form int(11) NULL,
                width_image int(11) NULL,
                width_maps_block int(11) NULL,
	            date_add datetime NULL,

				PRIMARY KEY (`id_contactform`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
        Db::getInstance()->execute($sql);
        // Table  pages lang
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'contactform_lang';
        Db::getInstance()->execute($sql);
        $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'contactform_lang(
				id_contactform int(11) unsigned NOT NULL,
				id_lang int(11) unsigned NOT NULL,
				id_shop int(11) unsigned NOT NULL,
				title_block_description varchar(512) NULL,
				description varchar(2000) NULL,
				title_block_form varchar(512) NULL,
				title_block_image varchar(512) NULL,
				title_block_maps varchar(512) NULL,
				PRIMARY KEY(id_contactform, id_shop, id_lang)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
        Db::getInstance()->execute($sql);
    }

    public function uninstallDb()
    {
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'contactform';
        Db::getInstance()->execute($sql);
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'contactform_lang';
        Db::getInstance()->execute($sql);
    }

    public function setInDb()
    {
        $languages = Language::getLanguages(false);
        $obj = new ContactFormClass();
        foreach ($languages as $lang) {
            $obj->title_block_description[$lang['id_lang']] = 'Contact Details';
            $obj->description[$lang['id_lang']] = $this->getDescriptionFormDef();
            $obj->title_block_form[$lang['id_lang']] = 'Send message';
            $obj->title_block_image[$lang['id_lang']] = 'Our manager will contact you';
            $obj->title_block_maps[$lang['id_lang']] = 'Our location';
        }
        $obj->email = 'demo@demo.com';
        $obj->background = '#ffffff';
        $obj->color = '#100000';
        $obj->block_description = 1;
        $obj->block_form = 1;
        $obj->block_image = 1;
        $obj->block_maps = 1;
        $obj->position_description = 'left';
        $obj->position_form = 'center';
        $obj->position_image = 'right';
        $obj->position_maps = 'bottom';
        $obj->name_field = 1;
        $obj->email_field = 1;
        $obj->phone_field = 1;
        $obj->subject_field = 0;
        $obj->captcha_field = 1;
        $obj->attach_field = 0;
        $obj->name_field_required = 1;
        $obj->email_field_required = 0;
        $obj->phone_field_required = 1;
        $obj->subject_field_required = 0;
        $obj->background_button = '#7bae23';
        $obj->background_button_hover = '#629112';
        $obj->color_button = '#ffffff';
        $obj->maps_code = $this->getMapsCodeDef();
        $obj->width_maps = 1720;
        $obj->height_maps = 400;
        $obj->width_description = 33;
        $obj->width_form = 33;
        $obj->width_image = 33;
        $obj->width_maps_block = 100;
        $obj->save();
    }

    public function getMapsCodeDef()
    {
        return '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d167998.10803373056!2d2.2074740643680624!3d48.85877410312378!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e1f06e2b70f%3A0x40b82c3688c9460!2z0J_QsNGA0LjQtiwg0KTRgNCw0L3RhtGW0Y8!5e0!3m2!1suk!2sua!4v1455005606039" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>';
    }

    public function getDescriptionFormDef()
    {
        return '<h4> <span class="material-icons-phone"><i class="material-icons">&#xE0CD;</i></span> <strong> Phone</strong></h4>
			<p>             (099) 583-34-40</p>
			<p>             (093) 552-97-33</p>
			<p>             (098) 384-99-19</p>
			<span class="button_line"></span>
			<h4><i class="material-icons">&#xE8B4;</i> <strong> Adress</strong></h4>
			<p>             Mr John Smith</p>
			<p>             132, My street</p>
			<p>             Kingston, New York 1240</p>
			<p>             example.example@msn.com</p>
	  	<span class="button_line"></span>
			<h4><i class="material-icons">&#xE192;</i>  <strong>Working day</strong></h4>
			<p>             Monday 9:00 - 20:00</p>
			<p>             Saturdey 10:00 - 17:00</p>
			<p>             Sunday Holiday</p>
			<span class="button_line"></span>';
    }

    public function getContent()
    {
        $settings = $this->_contactFormClass->getContactForm(Context::getContext()->language->id,
            Context::getContext()->shop->id);
        if (isset($settings[0]) && $settings[0]) {
            $settings = $settings[0];
        }
        if (!$settings) {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminContactForm') . '&addcontactform');
        } else {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminContactForm') . '&updatecontactform&id_contactform=' . $settings['id_contactform']);
        }
    }

    public function getBreadcrumb()
    {
        return $this->l('Contacts');
    }

    public function hookHeader()
    {
        if ($this->context->controller->php_self == 'contact') {
            Context::getContext()->shop->theme->setPageLayouts(["contact" => "layout-full-width"]);
        }
        Media::addJsDef(
            [
                'is_mobile' => Context::getContext()->getMobileDevice(),
            ]
        );
    }

    public function hookDisplayOverrideTemplate($param)
    {
        if (isset($this->context->controller->php_self) && ($this->context->controller->php_self == 'contact')) {
            $this->context->controller->registerStylesheet('mpm_contactform',
                'modules/mpm_contactform/views/css/contactform.css', ['media' => 'all', 'priority' => 900]);
            $this->context->controller->registerJavascript('mpm_contactform',
                'modules/mpm_contactform/views/js/contactform.js', ['position' => 'bottom', 'priority' => 150]);
            $settings = $this->getContactFormSettings(Context::getContext()->language->id,
                Context::getContext()->shop->id);
            if (isset($settings[0]['id_contactform']) && $settings[0]['id_contactform']) {
                $settings = $settings[0];
            } else {
                return false;
            }
            $path_img = _PS_MODULE_DIR_ . '/mpm_contactform/views/img/' . $settings['id_contactform'] . '.png';
            $images = false;
            if (file_exists($path_img)) {
                $images = _MODULE_DIR_ . '/mpm_contactform/views/img/' . $settings['id_contactform'] . '.png';
            }
            if ($settings['block_maps']) {
                $dom = new DOMDocument();
                $dom->loadHTML($settings['maps_code']);
                $iframe = $dom->getElementsByTagName('iframe')->item(0);
                $src = $iframe->getAttribute('src');
            } else {
                $src = false;
            }
            $this->context->smarty->assign(
                [
                    'baseUrl'  => _MODULE_DIR_ . 'mpm_contactform/',
                    'settings' => $settings,
                    'images'   => $images,
                    'maps'     => $src,
                    'base_url'    => _PS_BASE_URL_SSL_ . __PS_BASE_URI__,
                    'captcha_url' => _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'modules/mpm_contactform/secpic.php',
                    'path'        => $this->getBreadcrumb(),
                    'id_shop'     => Context::getContext()->shop->id,
                    'id_lang'     => Context::getContext()->language->id,
                ]);
            return $this->getTemplatePath('blockcontactform.tpl');
//      return $this->display(__FILE__, 'views/templates/hook/blockcontactform.tpl');
        }
    }

    public function getContactFormSettings($id_lang, $id_shop)
    {
        $sql = '
			SELECT *
      FROM ' . _DB_PREFIX_ . 'contactform as c
      INNER JOIN ' . _DB_PREFIX_ . 'contactform_lang as cl
      ON c.id_contactform = cl.id_contactform
      WHERE cl.id_lang = ' . (int)$id_lang . '
      AND cl.id_shop = ' . (int)$id_shop . '
			';
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }
}
