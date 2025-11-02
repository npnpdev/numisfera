<?php
if (!defined('_PS_VERSION_')) {
    exit;
}
require_once(dirname(__FILE__) . '/classes/blockMpmFeatured.php');

use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;

class mpm_homefeatured extends Module
{
    private $_homeFeatured;
    private $_idShop;
    private $_idLang;

    public function __construct()
    {
        $this->name = 'mpm_homefeatured';
        $this->tab = 'front_office_features';
        $this->version = '1.0.1';
        $this->author = 'MyPrestaModules';
        $this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Home featured');
        $this->description = $this->l('Home featured');
        $this->_homeFeatured = new blockMpmFeatured();
        $this->_idShop = Context::getContext()->shop->id;
        $this->_idLang = Context::getContext()->language->id;
    }

    public function install()
    {
        if (!parent::install()
            || !$this->registerHook('actionAdminControllerSetMedia')
            || !$this->registerHook('header')
            || !$this->registerhook('displayHomeContent1')
            || !$this->registerhook('displayHomeContent2')
            || !$this->registerhook('displayHomeContent3')
            || !$this->registerhook('displayHomeContent4')
            || !$this->registerhook('displayHomeContent5')
        ) {
            return false;
        }
        $this->_createTab('AdminMpmHomeFeatured', 'Home Featured');
        $this->_installDb();
        $this->_setDataDb();
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }
        $this->_removeTab('AdminMpmHomeFeatured');
        $this->_uninstallDb();
        return true;
    }

    public function hookActionAdminControllerSetMedia()
    {
        if (Tools::getValue('controller') != 'AdminMpmHomeFeatured') {
            return false;
        }

        $this->context->controller->addCSS([
            _PS_MODULE_DIR_.'mpm_homefeatured/views/css/mpm_homefeatured_admin.css'
        ]);

        $this->context->controller->addJS([
            _PS_MODULE_DIR_.'mpm_homefeatured/views/js/mpm_homefeatured_admin.js',
            __PS_BASE_URI__.'js/jquery/plugins/select2/jquery.select2.js'
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

    private function _installDb()
    {
        // Table  pages
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'mpm_homefeatured';
        Db::getInstance()->execute($sql);
        $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'mpm_homefeatured(
				id_mpm_homefeatured int(11) unsigned NOT NULL AUTO_INCREMENT,
				active boolean NOT NULL,
				type varchar(255) NULL,
				hook varchar(255) NULL,
				ids_categories varchar(255) NULL,
				ids_products varchar(255) NULL,
				date_add datetime NULL,
				PRIMARY KEY (`id_mpm_homefeatured`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
        Db::getInstance()->execute($sql);
        // Table  pages lang
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'mpm_homefeatured_lang';
        Db::getInstance()->execute($sql);
        $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'mpm_homefeatured_lang(
				id_mpm_homefeatured int(11) unsigned NOT NULL,
				id_lang int(11) unsigned NOT NULL,
				id_shop int(11) unsigned NOT NULL,
				title varchar(255) NULL,
				PRIMARY KEY(id_mpm_homefeatured, id_shop, id_lang)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
        Db::getInstance()->execute($sql);
    }

    private function _uninstallDb()
    {
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'mpm_homefeatured';
        Db::getInstance()->execute($sql);
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'mpm_homefeatured_lang';
        Db::getInstance()->execute($sql);
    }

    private function _setDataDb()
    {
        $data = [
            ['type' => 'all', 'hook' => 'displayHomeContent1', 'title' => 'Featured Products'],
            ['type' => 'all', 'hook' => 'displayHomeContent2', 'title' => 'Our offers'],
            ['type' => 'new', 'hook' => 'displayHomeContent3', 'title' => 'New products'],
        ];
        foreach ($data as $value) {
            $this->_setItem($value['type'], $value['title'], $value['hook']);
        }
    }

    private function _setItem($type, $title, $hook)
    {
        $languages = Language::getLanguages(false);
        $obj = new blockMpmFeatured();
        foreach ($languages as $lang) {
            $obj->title[$lang['id_lang']] = $title;
        }
        $obj->hook = $hook;
        $obj->type = $type;
        $obj->active = 1;
        $obj->save();
    }

    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminMpmHomeFeatured'));
    }

    public function hookDisplayHeader($params)
    {
        $this->context->controller->registerStylesheet('mpm_homefeatured1',
            'modules/' . $this->name . '/views/css/mpm_homefeatured.css', ['media' => 'all', 'priority' => 900]);
        $this->context->controller->registerStylesheet('slick', 'modules/' . $this->name . '/views/css/slick.css',
            ['media' => 'all', 'priority' => 900]);
        $this->context->controller->registerJavascript('mpm_homefeatured1',
            'modules/' . $this->name . '/views/js/mpm_homefeatured.js', ['position' => 'bottom', 'priority' => 150]);
        $this->context->controller->registerJavascript('slick', 'modules/' . $this->name . '/views/js/slick.min.js',
            ['position' => 'bottom', 'priority' => 150]);
        if (Context::getContext()->controller->php_self == 'product') {
            $id_product = (int)Tools::getValue('id_product');
            $productsViewed = (isset($params['cookie']->viewed) && !empty($params['cookie']->viewed)) ? array_slice(array_reverse(explode(',',
                $params['cookie']->viewed)), 0, 20) : [];
            if ($id_product && !in_array($id_product, $productsViewed)) {
                $product = new Product((int)$id_product);
                if ($product->checkAccess((int)$this->context->customer->id)) {
                    if (isset($params['cookie']->viewed) && !empty($params['cookie']->viewed)) {
                        $params['cookie']->viewed .= ',' . (int)$id_product;
                    } else {
                        $params['cookie']->viewed = (int)$id_product;
                    }
                }
            }
        }
    }

    public function hookDisplayHomeContent1()
    {
        $settings = $this->_homeFeatured->getSetiingsItem($this->_idLang, $this->_idShop, 'displayHomeContent1');

        if (!$settings) {
            return false;
        }
        foreach ($settings as $key => $value) {
            $products = $this->getProductsItem($value, $this->_idLang, $this->_idShop);
            if ($products) {
                $settings[$key]['products'] = $products;
            } else {
                unset($settings[$key]);
            }
        }
        if (!$settings) {
            return false;
        }
        $this->context->smarty->assign(
            [
                'settings' => $settings,
                'id_shop'  => $this->_idShop,
                'id_lang'  => $this->_idLang,
            ]
        );
        return $this->display(__FILE__, 'views/templates/hook/itemSlider.tpl');
    }

    public function hookDisplayHomeContent2()
    {
        $settings = $this->_homeFeatured->getSetiingsItem($this->_idLang, $this->_idShop, 'displayHomeContent2');

        if (!$settings) {
            return false;
        }


        foreach ($settings as $key => $value) {
            $products = $this->getProductsItem($value, $this->_idLang, $this->_idShop);

            if ($products) {
                $settings[$key]['products'] = $products;
            } else {
                unset($settings[$key]);
            }
        }
        if (!$settings) {
            return false;
        }
        $this->context->smarty->assign(
            [
                'settings' => $settings,
                'id_shop'  => $this->_idShop,
                'id_lang'  => $this->_idLang,
            ]
        );
        return $this->display(__FILE__, 'views/templates/hook/itemSlider.tpl');
    }

    public function hookDisplayHomeContent3()
    {
        $settings = $this->_homeFeatured->getSetiingsItem($this->_idLang, $this->_idShop, 'displayHomeContent3');
        if (!$settings) {
            return false;
        }
        foreach ($settings as $key => $value) {
            $products = $this->getProductsItem($value, $this->_idLang, $this->_idShop);
            if ($products) {
                $settings[$key]['products'] = $products;
            } else {
                unset($settings[$key]);
            }
        }
        if (!$settings) {
            return false;
        }
        $this->context->smarty->assign(
            [
                'settings' => $settings,
                'id_shop'  => $this->_idShop,
                'id_lang'  => $this->_idLang,
            ]
        );
        return $this->display(__FILE__, 'views/templates/hook/itemSlider.tpl');
    }

    public function hookDisplayHomeContent4()
    {
        $settings = $this->_homeFeatured->getSetiingsItem($this->_idLang, $this->_idShop, 'displayHomeContent4');
        if (!$settings) {
            return false;
        }
        foreach ($settings as $key => $value) {
            $products = $this->getProductsItem($value, $this->_idLang, $this->_idShop);
            if ($products) {
                $settings[$key]['products'] = $products;
            } else {
                unset($settings[$key]);
            }
        }
        if (!$settings) {
            return false;
        }
        $this->context->smarty->assign(
            [
                'settings' => $settings,
                'id_shop'  => $this->_idShop,
                'id_lang'  => $this->_idLang,
            ]
        );
        return $this->display(__FILE__, 'views/templates/hook/itemSlider.tpl');
    }

    public function hookDisplayHomeContent5()
    {
        $settings = $this->_homeFeatured->getSetiingsItem($this->_idLang, $this->_idShop, 'displayHomeContent5');
        if (!$settings) {
            return false;
        }
        foreach ($settings as $key => $value) {
            $products = $this->getProductsItem($value, $this->_idLang, $this->_idShop);
            if ($products) {
                $settings[$key]['products'] = $products;
            } else {
                unset($settings[$key]);
            }
        }
        if (!$settings) {
            return false;
        }
        $this->context->smarty->assign(
            [
                'settings' => $settings,
                'id_shop'  => $this->_idShop,
                'id_lang'  => $this->_idLang,
            ]
        );
        return $this->display(__FILE__, 'views/templates/hook/itemSlider.tpl');
    }

    public function getProductsItem($value, $id_lang, $id_shop)
    {
        $array_result = [];

        $ids = $this->getIdsProducts($value['type'], $value['ids_categories'], $value['ids_products']);

        if (!$ids) {
            return false;
        }
        $products = $this->getProductsByIds($id_lang, $id_shop, $ids);
        $assembler = new ProductAssembler($this->context);
        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new ProductListingPresenter(
            new ImageRetriever(
                $this->context->link
            ),
            $this->context->link,
            new PriceFormatter(),
            new ProductColorsRetriever(),
            $this->context->getTranslator()
        );
        $array_result = [];
        foreach ($products as $prow) {
            $array_result[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($prow),
                $this->context->language
            );
        }
        return $array_result;
    }

    public function getIdsProducts($type, $categories, $products)
    {
        $ids = false;

        if ($type == 'all') {
            $ids = $this->getIdsAllProducts();
        }
        if ($type == 'category') {
            if ($categories) {
                $ids = $this->getIdsProductsInCategory($categories);
            } else {
                $ids = false;
            }
        }
        if ($type == 'products') {
            if ($products) {
                $ids = $products;
            } else {
                $ids = false;
            }
        }
        if ($type == 'last_visited') {
            $ids = (isset(Context::getContext()->cookie->viewed) && !empty(Context::getContext()->cookie->viewed)) ? array_slice(array_reverse(explode(',',
                Context::getContext()->cookie->viewed)), 0, 40) : [];
            $ids = implode(',', $ids);
        }
        if ($type == 'discount') {
            $ids = $this->getIdsProductsDiscount();
        }
        if ($type == 'selling') {
            $ids = $this->getIdsProductsSale();
        }
        if ($type == 'new') {
            $ids = $this->getIdsNewProducts();
        }
        return $ids;
    }

    public function getIdsAllProducts()
    {
        $sql = 'SELECT GROUP_CONCAT(x.id_product) as id_product FROM (
                    SELECT p.id_product as id_product
                    FROM ' . _DB_PREFIX_ . 'product as p
                    WHERE p.active = 1
                    LIMIT 20
                ) as x ';


        $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        if (isset($res[0]['id_product']) && $res[0]['id_product']) {
            return $res[0]['id_product'];
        } else {
            return false;
        }
    }

    public function getIdsProductsInCategory($ids)
    {
        $sql = '
        SELECT GROUP_CONCAT(x.id_product) as id_product FROM (
            SELECT DISTINCT cp.id_product as id_product
            FROM ' . _DB_PREFIX_ . 'category_product as cp
            INNER JOIN ' . _DB_PREFIX_ . 'product as p
            ON p.id_product = cp.id_product
            WHERE p.active = 1
            AND cp.id_category IN(' . pSQL($ids) . ')
            LIMIT 20
        ) as x';

        $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        if (isset($res[0]['id_product']) && $res[0]['id_product']) {
            return $res[0]['id_product'];
        } else {
            return false;
        }
    }

    public function getIdsProductsDiscount()
    {
        $sql = '
            SELECT GROUP_CONCAT(x.id_product) as id_product FROM (
                SELECT DISTINCT p.id_product as id_product
                FROM ' . _DB_PREFIX_ . 'product as p
                LEFT JOIN ' . _DB_PREFIX_ . 'specific_price as sp
                ON p.id_product = sp.id_product
                WHERE p.active = 1
                 AND sp.id_specific_price IS NOT NULL
                 LIMIT 20
            ) as x';

        $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        if (isset($res[0]['id_product']) && $res[0]['id_product']) {
            return $res[0]['id_product'];
        } else {
            return false;
        }
    }

    public function getIdsProductsSale()
    {
        $sql = '
            SELECT GROUP_CONCAT(x.id_product) as id_product FROM (
                SELECT DISTINCT p.id_product as id_product
                FROM ' . _DB_PREFIX_ . 'product_sale as ps
                INNER JOIN ' . _DB_PREFIX_ . 'product as p
                ON p.id_product = ps.id_product
                WHERE p.active = 1
                LIMIT 20
            ) as x';

        $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        if (isset($res[0]['id_product']) && $res[0]['id_product']) {
            return $res[0]['id_product'];
        } else {
            return false;
        }
    }

    public function getIdsNewProducts()
    {
        $sql = '
        SELECT GROUP_CONCAT(id_product) as id_product
        FROM(
        SELECT DISTINCT p.id_product as id_product
        FROM ' . _DB_PREFIX_ . 'product as p
        WHERE p.active = 1
        ORDER BY p.date_add DESC
        LIMIT 20
        ) as id_product
        ';
        $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        if (isset($res[0]['id_product']) && $res[0]['id_product']) {
            return $res[0]['id_product'];
        } else {
            return false;
        }
    }

    public function getCategoriesFeatured($ids, $id_lang, $id_shop)
    {
        $sql = '
           SELECT cp.id_category, cl.name, cl.link_rewrite, count(cp.id_category) as count_products
          FROM ' . _DB_PREFIX_ . 'category_product as cp
          INNER JOIN ' . _DB_PREFIX_ . 'category as c
          ON c.id_category = cp.id_category
          LEFT JOIN ' . _DB_PREFIX_ . 'category_lang as cl
          ON c.id_category = cl.id_category
          WHERE c.active = 1
          AND cl.id_shop = ' . (int)$id_shop . '
          AND cl.id_lang = ' . (int)$id_lang . '
          AND cp.id_product IN(' . pSQL($ids) . ')
          AND cp.id_category != 2
          GROUP BY cp.id_category
        ';
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    public function getProductsByIds($id_lang, $id_shop, $ids)
    {
        $sql = '
			SELECT pl.name, p.*, i.id_image, pl.link_rewrite, p.reference
      FROM ' . _DB_PREFIX_ . 'product_lang as pl
      LEFT JOIN ' . _DB_PREFIX_ . 'image as i
      ON i.id_product = pl.id_product AND i.cover=1
      INNER JOIN ' . _DB_PREFIX_ . 'product as p
      ON p.id_product = pl.id_product
      LEFT JOIN ' . _DB_PREFIX_ . 'category_product as cp
      ON p.id_product = cp.id_product
      WHERE pl.id_lang = ' . (int)$id_lang . '
      AND pl.id_shop = ' . (int)$id_shop . '
      AND p.id_product IN (' . pSQL($ids) . ')
      GROUP BY p.id_product
      ORDER BY FIELD(p.id_product, ' . pSQL($ids) . ')
			';
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }
}