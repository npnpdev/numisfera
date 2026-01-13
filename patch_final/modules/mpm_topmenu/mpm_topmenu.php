<?php
if (!defined('_PS_VERSION_')) {
    exit;
}
require_once(dirname(__FILE__) . '/classes/topMenu.php');

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;

class mpm_topmenu extends Module
{
    private $_idShop;
    private $_idLang;
    private $_css;

    public function __construct()
    {
        $this->name = 'mpm_topmenu';
        $this->tab = 'front_office_features';
        $this->version = '1.0.1';
        $this->author = 'MyPrestaModules';
        $this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Advanced Top Menu');
        $this->description = $this->l('Advanced Top Menu.');
        $this->_idShop = Context::getContext()->shop->id;
        $this->_idLang = Context::getContext()->language->id;
    }

    public function install()
    {
        if (!parent::install()
            || !$this->registerHook('actionAdminControllerSetMedia')
            || !$this->registerHook('displayBeforeBodyClosingTag')
            || !$this->registerHook('header')
            || !$this->registerhook('displayTopMenu')
        ) {
            return false;
        }

        $this->_createTab('AdminTopMenu', 'Top Menu');
        $this->_createTab('AdminTopMenuColumn', 'Top Menu Column');
        $this->_createTab('AdminTopMenuGroup', 'Top Menu Group');
        $this->_createTab('AdminTopMenuLink', 'Top Menu Link');
        $this->_installDb();
        $this->_setDataDb();
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }
        $this->_removeTab('AdminTopMenu');
        $this->_removeTab('AdminTopMenuColumn');
        $this->_removeTab('AdminTopMenuGroup');
        $this->_removeTab('AdminTopMenuLink');
        $this->_uninstallDb();
        return true;
    }

    public function hookActionAdminControllerSetMedia()
    {
        if (Tools::getValue('controller') != 'AdminTopMenu') {
            return false;
        }

        $this->context->controller->addCSS([
            _PS_MODULE_DIR_ . 'mpm_topmenu/views/css/mpm_topmenu_admin.css'
        ]);

        $this->context->controller->addJS([
            _PS_MODULE_DIR_ . 'mpm_topmenu/views/js/mpm_topmenu_admin.js',
            __PS_BASE_URI__ . 'js/jquery/plugins/select2/jquery.select2.js',
            __PS_BASE_URI__ . 'js/jquery/ui/jquery.ui.sortable.min.js'
        ]);

        $this->context->controller->addjQueryPlugin([
            'select2',
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
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'topmenu';
        Db::getInstance()->execute($sql);
        $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'topmenu(
				id_topmenu int(11) unsigned NOT NULL AUTO_INCREMENT,
				active boolean NOT NULL,
				position int(11) unsigned NOT NULL,
				open_new_window boolean NOT NULL,
				text_color_tab varchar(255) NULL,
				text_color_hover_tab varchar(255) NULL,
				background_color_tab varchar(255) NULL,
				background_color_hover_tab varchar(255) NULL,
				width int(11) unsigned NOT NULL,
				min_height int(11) unsigned NOT NULL,
        border_size int(11) unsigned NOT NULL,
				border_color varchar(255) NULL,
				background_color varchar(255) NULL,
				date_add datetime NULL,
				PRIMARY KEY (`id_topmenu`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
        Db::getInstance()->execute($sql);
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'topmenu_lang';
        Db::getInstance()->execute($sql);
        $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'topmenu_lang(
				id_topmenu int(11) unsigned NOT NULL,
				id_lang int(11) unsigned NOT NULL,
				id_shop int(11) unsigned NOT NULL,
				title varchar(255) NULL,
				link varchar(255) NULL,
				description_before TEXT  NOT NULL,
				description_after TEXT  NOT NULL,
				
				PRIMARY KEY(id_topmenu, id_shop, id_lang)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
        Db::getInstance()->execute($sql);
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'topmenu_column';
        Db::getInstance()->execute($sql);
        $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'topmenu_column(
				id_topmenu_column int(11) unsigned NOT NULL AUTO_INCREMENT,
				id_topmenu int(11) unsigned NOT NULL,
				ident int(11) unsigned NOT NULL,
				active boolean NOT NULL,
				title varchar(255) NULL,
				position int(11) unsigned NOT NULL,
				text_color varchar(255) NULL,
				text_color_hover varchar(255) NULL,
				background_color varchar(255) NULL,
				width int(11) unsigned NOT NULL,
				date_add datetime NULL,
				PRIMARY KEY (`id_topmenu_column`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
        Db::getInstance()->execute($sql);
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'topmenu_column_lang';
        Db::getInstance()->execute($sql);
        $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'topmenu_column_lang(
				id_topmenu_column int(11) unsigned NOT NULL,
				id_lang int(11) unsigned NOT NULL,
				id_shop int(11) unsigned NOT NULL,
				description_before TEXT  NOT NULL,
				description_after TEXT  NOT NULL,
				
				PRIMARY KEY(id_topmenu_column, id_shop, id_lang)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
        Db::getInstance()->execute($sql);
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'topmenu_group';
        Db::getInstance()->execute($sql);
        $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'topmenu_group(
				id_topmenu_group int(11) unsigned NOT NULL AUTO_INCREMENT,
				id_topmenu_column int(11) unsigned NOT NULL,
				id_topmenu int(11) unsigned NOT NULL,
				ident int(11) unsigned NOT NULL,
				active boolean NOT NULL,
				title varchar(255) NULL,
				type varchar(255) NULL,
				subcategories int(11) unsigned NOT NULL,
				product_title int(11) unsigned NOT NULL,
				product_img int(11) unsigned NOT NULL,
				product_price int(11) unsigned NOT NULL,
				product_add int(11) unsigned NOT NULL,
				type_img varchar(255) NULL,
				categories varchar(1000) NULL,
				products varchar(1000) NULL,
				cms varchar(1000) NULL,
				link varchar(1000) NULL,
				brands varchar(1000) NULL,
				suppliers varchar(1000) NULL,
				pages varchar(1000) NULL,
				images varchar(1000) NULL,
				video varchar(1000) NULL,
				position int(11) unsigned NOT NULL,
				text_color varchar(255) NULL,
				text_color_hover varchar(255) NULL,
				background_color varchar(255) NULL,
				date_add datetime NULL,
				
				PRIMARY KEY (`id_topmenu_group`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
        Db::getInstance()->execute($sql);
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'topmenu_group_lang';
        Db::getInstance()->execute($sql);
        $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'topmenu_group_lang(
				id_topmenu_group int(11) unsigned NOT NULL,
				id_lang int(11) unsigned NOT NULL,
				id_shop int(11) unsigned NOT NULL,
				title_front varchar(255) NULL,
				description TEXT  NOT NULL,
				description_before TEXT  NOT NULL,
				description_after TEXT  NOT NULL,
				
				PRIMARY KEY(id_topmenu_group, id_shop, id_lang)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
        Db::getInstance()->execute($sql);
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'topmenu_link';
        Db::getInstance()->execute($sql);
        $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'topmenu_link(
				id_topmenu_link int(11) unsigned NOT NULL AUTO_INCREMENT,
				active boolean NOT NULL,
				date_add datetime NULL,
				PRIMARY KEY (`id_topmenu_link`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
        Db::getInstance()->execute($sql);
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'topmenu_link_lang';
        Db::getInstance()->execute($sql);
        $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'topmenu_link_lang(
				id_topmenu_link int(11) unsigned NOT NULL,
				id_lang int(11) unsigned NOT NULL,
				id_shop int(11) unsigned NOT NULL,
				title varchar(255) NULL,
				link TEXT  NOT NULL,
				PRIMARY KEY(id_topmenu_link, id_shop, id_lang)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8';
        Db::getInstance()->execute($sql);
    }

    private function _uninstallDb()
    {
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'topmenu';
        Db::getInstance()->execute($sql);
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'topmenu_lang';
        Db::getInstance()->execute($sql);
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'topmenu_column';
        Db::getInstance()->execute($sql);
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'topmenu_column_lang';
        Db::getInstance()->execute($sql);
    }

    private function _setDataDb()
    {
        $data = [
            [
                'title'           => 'Blog',
                'link'            => Tools::getShopDomainSsl(true) . 'blog/',
                'open_new_window' => 0,
            ],
            [
                'title'           => 'Contact us',
                'link'            => $this->context->link->getPageLink('contact'),
                'open_new_window' => 0,
            ],
        ];
        foreach ($data as $value) {
            $this->_setItem($value);
        }
    }

    private function _setItem($value)
    {
        $languages = Language::getLanguages(false);
        $obj = new topMenu();
        foreach ($languages as $lang) {
            $obj->title[$lang['id_lang']] = $value['title'];
            $obj->link[$lang['id_lang']] = $value['link'];
        }
        $obj->open_new_window = $value['open_new_window'];
        $obj->active = 1;
        $obj->save();
    }

    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminTopMenu'));
    }

    public function hookDisplayBeforeBodyClosingTag()
    {
        return $this->_css;
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->registerStylesheet('mpm_homefeatured',
            'modules/' . $this->name . '/views/css/mpm_topmenu.css', ['media' => 'all', 'priority' => 900]);
        $this->context->controller->registerJavascript('mpm_homefeatured',
            'modules/' . $this->name . '/views/js/mpm_topmenu.js', ['position' => 'bottom', 'priority' => 150]);
    }

    public function hookDisplayTopMenu()
    {
        $menu_items = $this->getMenuItems($this->_idLang, $this->_idShop);
        foreach ($menu_items as $key => $value) {
            $menu_items[$key]['columns'] = $this->getContentColumns($this->_idLang, $this->_idShop,
                $value['id_topmenu']);
            $this->_css .= $this->getItemCss($value);
        }
        $this->context->smarty->assign(
            [
                'id_shop'    => $this->_idShop,
                'id_lang'    => $this->_idLang,
                'menu_items' => $menu_items,
            ]
        );
        return $this->display(__FILE__, 'views/templates/front/menu.tpl');
    }

    public function getContentColumns($id_lang, $id_shop, $id_topmenu)
    {
        $columns = $this->getColumns($id_lang, $id_shop, $id_topmenu);
        if (!$columns) {
            return false;
        }
        foreach ($columns as $key => $value) {
            $columns[$key]['groups'] = $this->getContentGroups($id_lang, $id_shop, $value['id_topmenu_column']);
            $this->_css .= $this->getItemColumnCss($value);
        }
        $this->context->smarty->assign(
            [
                'id_shop'    => $id_shop,
                'id_lang'    => $id_lang,
                'id_topmenu' => $id_topmenu,
                'columns'    => $columns,
            ]
        );
        return $this->display(__FILE__, 'views/templates/front/columns.tpl');
    }

    public function getItemColumnCss($value)
    {
        $this->context->smarty->assign(
            [
                'value' => $value,
            ]
        );
        return $this->display(__FILE__, 'views/templates/front/item_column_css.tpl');
    }

    public function getItemGroupCss($value)
    {
        $this->context->smarty->assign(
            [
                'value' => $value,
            ]
        );
        return $this->display(__FILE__, 'views/templates/front/item_group_css.tpl');
    }

    public function getItemCss($value)
    {
        $this->context->smarty->assign(
            [
                'value' => $value,
            ]
        );
        return $this->display(__FILE__, 'views/templates/front/item_css.tpl');
    }

    public function getContentGroups($id_lang, $id_shop, $id_topmenu_column)
    {
        $groups = $this->getItemGroups($id_lang, $id_shop, $id_topmenu_column);
        foreach ($groups as $key => $group) {
            $groups[$key]['group'] = $this->getContentGroup($id_lang, $id_shop, $group['id_topmenu_group']);
            $this->_css .= $this->getItemGroupCss($group);
        }
        $this->context->smarty->assign(
            [
                'id_shop'           => $id_shop,
                'id_lang'           => $id_lang,
                'id_topmenu_column' => $id_topmenu_column,
                'groups'            => $groups,
            ]
        );
        return $this->display(__FILE__, 'views/templates/front/groups.tpl');
    }

    public function getContentGroup($id_lang, $id_shop, $id_topmenu_group)
    {
        $value = '';
        $group = $this->getItemGroup($id_lang, $id_shop, $id_topmenu_group);
        if (isset($group[0]) && $group[0]) {
            $value = $group[0];
        }
        $value['tpl'] = $this->getGroupTpl($id_lang, $id_shop, $value);
        $this->context->smarty->assign(
            [
                'id_shop'          => $id_shop,
                'id_lang'          => $id_lang,
                'id_topmenu_group' => $id_topmenu_group,
                'group'            => $value,
            ]
        );
        return $this->display(__FILE__, 'views/templates/front/group.tpl');
    }

    public function getGroupTpl($id_lang, $id_shop, $value)
    {
        $tpl = "";
        $type = $value['type'];
        if ($type == 'product') {
            $tpl = $this->getProductBlock($id_lang, $id_shop, $value);
        }
        if ($type == 'category') {
            $tpl = $this->getCategoriesBlock($id_lang, $id_shop, $value['categories'], $value['subcategories']);
        }
        if ($type == 'cms') {
            $tpl = $this->getCmsBlock($id_lang, $id_shop, $value['cms']);
        }
        if ($type == 'link') {
            $tpl = $this->getLinksBlock($id_lang, $id_shop, $value['link']);
        }
        if ($type == 'brand') {
            $tpl = $this->getBrandsBlock($id_lang, $id_shop, $value['brands']);
        }
        if ($type == 'supplier') {
            $tpl = $this->getSuppliersBlock($id_lang, $id_shop, $value['suppliers']);
        }
        if ($type == 'page') {
            $tpl = $this->getPagesBlock($id_lang, $id_shop, $value['pages']);
        }
        if ($type == 'description') {
            $tpl = $this->getDescriptionBlock($id_lang, $id_shop, $value['description']);
        }
        if ($type == 'image') {
            $tpl = $this->getImageBlock($id_lang, $id_shop, $value['id_topmenu_group']);
        }
        return $tpl;
    }

    public function getImageBlock($id_lang, $id_shop, $value)
    {
        $link = false;
        $imgExists = dirname(__FILE__) . '/views/img/';
        $imgDirTopMenu = _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'modules/mpm_topmenu/views/img/';
        $file_path = $imgExists . $value . '.png';
        $isset = file_exists($file_path);
        if ($isset) {
            $link = $imgDirTopMenu . $value . '.png';
        }
        $this->context->smarty->assign(
            [
                'id_shop' => $id_shop,
                'id_lang' => $id_lang,
                'link'    => $link,
            ]
        );
        return $this->display(__FILE__, 'views/templates/front/imageBlock.tpl');
    }

    public function getDescriptionBlock($id_lang, $id_shop, $value)
    {
        $this->context->smarty->assign(
            [
                'id_shop' => $id_shop,
                'id_lang' => $id_lang,
                'value'   => $value,
            ]
        );
        return $this->display(__FILE__, 'views/templates/front/descriptionBlock.tpl');
    }

    public function getPagesBlock($id_lang, $id_shop, $value)
    {
        $links = [];
        $ids = explode(",", $value);
        foreach ($ids as $key => $val) {
            $page = Meta::getMetaByPage($val, $id_lang);
            $links[$key]['title'] = $page['title'];
            $links[$key]['link'] = Context::getContext()->link->getPageLink($page['page'], null, $id_lang);
        }
        $this->context->smarty->assign(
            [
                'id_shop' => $id_shop,
                'id_lang' => $id_lang,
                'links'   => $links,
            ]
        );
        return $this->display(__FILE__, 'views/templates/front/linksBlock.tpl');
    }

    public function getSuppliersBlock($id_lang, $id_shop, $value)
    {
        $links = [];
        $ids = explode(",", $value);
        foreach ($ids as $key => $val) {
            $links[$key]['title'] = Supplier::getNameById($val);
            $links[$key]['link'] = Context::getContext()->link->getSupplierLink((int)$val, null, (int)$id_lang,
                $id_shop);
        }
        $this->context->smarty->assign(
            [
                'id_shop' => $id_shop,
                'id_lang' => $id_lang,
                'links'   => $links,
            ]
        );
        return $this->display(__FILE__, 'views/templates/front/linksBlock.tpl');
    }

    public function getBrandsBlock($id_lang, $id_shop, $value)
    {
        $links = [];
        $ids = explode(",", $value);
        foreach ($ids as $key => $val) {
            $links[$key]['title'] = Manufacturer::getNameById($val);
            $links[$key]['link'] = Context::getContext()->link->getManufacturerLink((int)$value, null, null,
                (int)$id_lang, $id_shop);
        }
        $this->context->smarty->assign(
            [
                'id_shop' => $id_shop,
                'id_lang' => $id_lang,
                'links'   => $links,
            ]
        );
        return $this->display(__FILE__, 'views/templates/front/linksBlock.tpl');
    }

    public function getLinksBlock($id_lang, $id_shop, $value)
    {
        $links = $this->getLinks($id_lang, $id_shop, $value);
        $this->context->smarty->assign(
            [
                'id_shop' => $id_shop,
                'id_lang' => $id_lang,
                'links'   => $links,
            ]
        );
        return $this->display(__FILE__, 'views/templates/front/linksBlock.tpl');
    }

    public function getCmsBlock($id_lang, $id_shop, $value)
    {
        $cms = $this->getCmsByIds($id_lang, $id_shop, $value);
        foreach ($cms as $key => $val) {
            $cms[$key]['link'] = Context::getContext()->link->getCMSLink((int)$val['id_cms'], null, null, (int)$id_lang,
                $id_shop);
            $cms[$key]['title'] = $cms[$key]['meta_title'];
        }
        $this->context->smarty->assign(
            [
                'id_shop' => $id_shop,
                'id_lang' => $id_lang,
                'links'   => $cms,
            ]
        );
        return $this->display(__FILE__, 'views/templates/front/linksBlock.tpl');
    }

    public function getCategoriesBlock($id_lang, $id_shop, $categories, $subcategories)
    {
        $ids = explode(",", $categories);
        $cat = [];
        foreach ($ids as $val) {
            $category = new Category((int)$val, $this->context->language->id);
            $cat[] = $this->getCategories($category);
        }
        $this->context->smarty->assign(
            [
                'id_shop'       => $id_shop,
                'id_lang'       => $id_lang,
                'categories'    => $cat,
                'subcategories' => $subcategories,
            ]
        );
        return $this->display(__FILE__, 'views/templates/front/categoryBlock.tpl');
    }

    private function getCategories($category)
    {
        $range = '';
        $maxdepth = 5;
        if (Validate::isLoadedObject($category)) {
            if ($maxdepth > 0) {
                $maxdepth += $category->level_depth;
            }
            $range = 'AND nleft >= ' . (int)$category->nleft . ' AND nright <= ' . (int)$category->nright;
        }
        $resultIds = [];
        $resultParents = [];
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT c.id_parent, c.id_category, cl.name, cl.description, cl.link_rewrite
			FROM `' . _DB_PREFIX_ . 'category` c
			INNER JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (c.`id_category` = cl.`id_category` AND cl.`id_lang` = ' . (int)$this->context->language->id . Shop::addSqlRestrictionOnLang('cl') . ')
			INNER JOIN `' . _DB_PREFIX_ . 'category_shop` cs ON (cs.`id_category` = c.`id_category` AND cs.`id_shop` = ' . (int)$this->context->shop->id . ')
			WHERE (c.`active` = 1 OR c.`id_category` = ' . (int)Configuration::get('PS_HOME_CATEGORY') . ')
			AND c.`id_category` != ' . (int)Configuration::get('PS_ROOT_CATEGORY') . '
			' . ((int)$maxdepth != 0 ? ' AND `level_depth` <= ' . (int)$maxdepth : '') . '
			' . $range . '
			AND c.id_category IN (
				SELECT id_category
				FROM `' . _DB_PREFIX_ . 'category_group`
				WHERE `id_group` IN (' . pSQL(implode(', ',
                Customer::getGroupsStatic((int)$this->context->customer->id))) . ')
			)
			ORDER BY `level_depth` ASC, ' . (Configuration::get('BLOCK_CATEG_SORT') ? 'cl.`name`' : 'cs.`position`') . ' ' . (Configuration::get('BLOCK_CATEG_SORT_WAY') ? 'DESC' : 'ASC'));
        foreach ($result as &$row) {
            $resultParents[$row['id_parent']][] = &$row;
            $resultIds[$row['id_category']] = &$row;
        }
        return $this->getTree($resultParents, $resultIds, $maxdepth, ($category ? $category->id : null));
    }

    public function getTree($resultParents, $resultIds, $maxDepth, $id_category = null, $currentDepth = 0)
    {
        if (is_null($id_category)) {
            $id_category = $this->context->shop->getCategory();
        }
        $children = [];
        if (isset($resultParents[$id_category]) && count($resultParents[$id_category]) && ($maxDepth == 0 || $currentDepth < $maxDepth)) {
            foreach ($resultParents[$id_category] as $subcat) {
                $children[] = $this->getTree($resultParents, $resultIds, $maxDepth, $subcat['id_category'],
                    $currentDepth + 1);
            }
        }
        if (isset($resultIds[$id_category])) {
            $link = $this->context->link->getCategoryLink($id_category, $resultIds[$id_category]['link_rewrite']);
            $name = $resultIds[$id_category]['name'];
            $desc = $resultIds[$id_category]['description'];
        } else {
            $link = $name = $desc = '';
        }
        return [
            'id'       => $id_category,
            'link'     => $link,
            'name'     => $name,
            'desc'     => $desc,
            'children' => $children
        ];
    }

    public function getProductBlock($id_lang, $id_shop, $value)
    {
        $products = [];
        if (isset($value['products']) && $value['products']) {
            $products = $this->getProductsByIds($id_lang, $id_shop, $value['products']);
        }
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
        $this->context->smarty->assign(
            [
                'id_shop'  => $id_shop,
                'id_lang'  => $id_lang,
                'title'    => $value['product_title'],
                'img'      => $value['product_img'],
                'price'    => $value['product_price'],
                'button'   => $value['product_add'],
                'type_img' => $value['type_img'],
                'products' => $array_result,
            ]
        );
        return $this->display(__FILE__, 'views/templates/front/productBlock.tpl');
    }

    public function getLinks($id_lang, $id_shop, $value)
    {
        $sql = '
			SELECT tml.link, tml.title
      FROM ' . _DB_PREFIX_ . 'topmenu_link as tm
      LEFT JOIN ' . _DB_PREFIX_ . 'topmenu_link_lang as tml
      ON tm.id_topmenu_link = tml.id_topmenu_link
      WHERE tml.id_lang = ' . (int)$id_lang . '
      AND tml.id_shop = ' . (int)$id_shop . '
      AND tm.id_topmenu_link IN (' . pSQL($value) . ')

			';
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    public function getCmsByIds($id_lang, $id_shop, $ids)
    {
        $sql = '
			SELECT *
      FROM ' . _DB_PREFIX_ . 'cms_lang as pl
      INNER JOIN ' . _DB_PREFIX_ . 'cms as p
      ON p.id_cms = pl.id_cms
      WHERE pl.id_lang = ' . (int)$id_lang . '
      AND pl.id_shop = ' . (int)$id_shop . '
      AND p.id_cms IN (' . pSQL($ids) . ')
      AND p.active = 1

			';
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    public function getProductsByIds($id_lang, $id_shop, $productsIds)
    {
        $sql = '
			SELECT pl.name, p.*, i.id_image, pl.link_rewrite, p.reference
      FROM ' . _DB_PREFIX_ . 'product_lang as pl
      LEFT JOIN ' . _DB_PREFIX_ . 'image as i
      ON i.id_product = pl.id_product AND i.cover=1
      INNER JOIN ' . _DB_PREFIX_ . 'product as p
      ON p.id_product = pl.id_product
      WHERE pl.id_lang = ' . (int)$id_lang . '
      AND pl.id_shop = ' . (int)$id_shop . '
      AND p.id_product IN (' . pSQL($productsIds) . ')
      ORDER BY rand() 
      LIMIT 1
			';
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    public function getItemGroup($id_lang, $id_shop, $id_topmenu_group)
    {
        $sql = '
			SELECT *
      FROM ' . _DB_PREFIX_ . 'topmenu_group as t
      INNER JOIN ' . _DB_PREFIX_ . 'topmenu_group_lang as tl
      ON t.id_topmenu_group = tl.id_topmenu_group
      WHERE tl.id_lang = ' . (int)$id_lang . '
      AND tl.id_shop = ' . (int)$id_shop . '
      AND t.active = 1
      AND t.id_topmenu_group =  ' . (int)$id_topmenu_group . '
			';
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    public function getItemGroups($id_lang, $id_shop, $id_topmenu_column)
    {
        $sql = '
			SELECT *
      FROM ' . _DB_PREFIX_ . 'topmenu_group as t
      INNER JOIN ' . _DB_PREFIX_ . 'topmenu_group_lang as tl
      ON t.id_topmenu_group = tl.id_topmenu_group
      WHERE tl.id_lang = ' . (int)$id_lang . '
      AND tl.id_shop = ' . (int)$id_shop . '
      AND t.active = 1
      AND t.id_topmenu_column =  ' . (int)$id_topmenu_column . '
      ORDER BY t.position

			';
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    public function getColumns($id_lang, $id_shop, $id_topmenu)
    {
        $sql = '
			SELECT *
      FROM ' . _DB_PREFIX_ . 'topmenu_column as t
      INNER JOIN ' . _DB_PREFIX_ . 'topmenu_column_lang as tl
      ON t.id_topmenu_column = tl.id_topmenu_column
      WHERE tl.id_lang = ' . (int)$id_lang . '
      AND tl.id_shop = ' . (int)$id_shop . '
      AND t.active = 1
      AND t.id_topmenu =  ' . (int)$id_topmenu . '
      ORDER BY t.position

			';
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    public function getMenuItems($id_lang, $id_shop)
    {
        $sql = '
			SELECT *
      FROM ' . _DB_PREFIX_ . 'topmenu as t
      INNER JOIN ' . _DB_PREFIX_ . 'topmenu_lang as tl
      ON t.id_topmenu = tl.id_topmenu
      WHERE tl.id_lang = ' . (int)$id_lang . '
      AND tl.id_shop = ' . (int)$id_shop . '
      AND t.active = 1
      ORDER BY t.position

			';
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }
}