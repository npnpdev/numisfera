<?php
/**
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA MILOSZ MYSZCZUK VATEU: PL9730945634
 * @copyright 2010-2025 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class verticalBlockCategories extends Module
{
    public $mypresta_link;
    public $mkey;

    public function __construct()
    {
        $this->name = 'verticalblockcategories';
        $this->tab = 'front_office_features';
        $this->version = '1.5.3';
        $this->mypresta_link = 'https://mypresta.eu/modules/front-office-features/vertical-dropdown-categories-menu.html';
        $this->author = 'mypresta.eu';
        $this->bootstrap = true;
        parent::__construct();
        $this->checkforupdates();
        $this->displayName = $this->l('verticalBlockCategories');
        $this->description = $this->l('Adds a block featuring product categories with vertical dropdown');
        $this->ps_versions_compliancy = array(
            'min' => '1.6',
            'max' => '999'
        );
    }

    public function checkforupdates($display_msg = 0, $form = 0)
    {
        // ---------- //
        // ---------- //
        // VERSION 16 //
        // ---------- //
        // ---------- //
        $this->mkey = "nlc";
        if (@file_exists('../modules/' . $this->name . '/key.php')) {
            @require_once('../modules/' . $this->name . '/key.php');
        } else {
            if (@file_exists(dirname(__FILE__) . $this->name . '/key.php')) {
                @require_once(dirname(__FILE__) . $this->name . '/key.php');
            } else {
                if (@file_exists('modules/' . $this->name . '/key.php')) {
                    @require_once('modules/' . $this->name . '/key.php');
                }
            }
        }
        if ($form == 1) {
            return '
            <div class="panel" id="fieldset_myprestaupdates" style="margin-top:20px;">
            ' . ($this->psversion() == 6 || $this->psversion() == 7 ? '<div class="panel-heading"><i class="icon-wrench"></i> ' . $this->l('MyPresta updates') . '</div>' : '') . '
			<div class="form-wrapper" style="padding:20px!important;">
            <div id="module_block_settings">
                    <fieldset id="fieldset_module_block_settings">
                         ' . ($this->psversion() == 5 ? '<legend style="">' . $this->l('MyPresta updates') . '</legend>' : '') . '
                        <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
                            <label>' . $this->l('Check updates') . '</label>
                            <div class="margin-form">' . (Tools::isSubmit('submit_settings_updates_now') ? ($this->inconsistency(0) ? '' : '') . $this->checkforupdates(1) : '') . '
                                <button style="margin: 0px; top: -3px; position: relative;" type="submit" name="submit_settings_updates_now" class="button btn btn-default" />
                                <i class="process-icon-update"></i>
                                ' . $this->l('Check now') . '
                                </button>
                            </div>
                            <label>' . $this->l('Updates notifications') . '</label>
                            <div class="margin-form">
                                <select name="mypresta_updates">
                                    <option value="-">' . $this->l('-- select --') . '</option>
                                    <option value="1" ' . ((int)(Configuration::get('mypresta_updates') == 1) ? 'selected="selected"' : '') . '>' . $this->l('Enable') . '</option>
                                    <option value="0" ' . ((int)(Configuration::get('mypresta_updates') == 0) ? 'selected="selected"' : '') . '>' . $this->l('Disable') . '</option>
                                </select>
                                <p class="clear">' . $this->l('Turn this option on if you want to check MyPresta.eu for module updates automatically. This option will display notification about new versions of this addon.') . '</p>
                            </div>
                            <label>' . $this->l('Module page') . '</label>
                            <div class="margin-form">
                                <a style="font-size:14px;" href="' . $this->mypresta_link . '" target="_blank">' . $this->displayName . '</a>
                                <p class="clear">' . $this->l('This is direct link to official addon page, where you can read about changes in the module (changelog)') . '</p>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" name="submit_settings_updates"class="button btn btn-default pull-right" />
                                <i class="process-icon-save"></i>
                                ' . $this->l('Save') . '
                                </button>
                            </div>
                        </form>
                    </fieldset>
                    <style>
                    #fieldset_myprestaupdates {
                        display:block;clear:both;
                        float:inherit!important;
                    }
                    </style>
                </div>
            </div>
            </div>';
        } else {
            if (defined('_PS_ADMIN_DIR_')) {
                if (Tools::isSubmit('submit_settings_updates')) {
                    Configuration::updateValue('mypresta_updates', Tools::getValue('mypresta_updates'));
                }
                if (Configuration::get('mypresta_updates') != 0 || (bool)Configuration::get('mypresta_updates') != false) {
                    if (Configuration::get('update_' . $this->name) < (date("U") - 259200)) {
                        $actual_version = verticalblockcategoriesUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version);
                    }
                    if (verticalblockcategoriesUpdate::version($this->version) < verticalblockcategoriesUpdate::version(Configuration::get('updatev_' . $this->name)) && Tools::getValue('ajax', 'false') == 'false') {
                        $this->context->controller->warnings[] = '<strong>' . $this->displayName . '</strong>: ' . $this->l('New version available, check http://MyPresta.eu for more informations') . ' <a href="' . $this->mypresta_link . '">' . $this->l('More details in changelog') . '</a>';
                        $this->warning = $this->context->controller->warnings[0];
                    }
                } else {
                    if (Configuration::get('update_' . $this->name) < (date("U") - 259200)) {
                        $actual_version = verticalblockcategoriesUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version);
                    }
                }
                if ($display_msg == 1) {
                    if (verticalblockcategoriesUpdate::version($this->version) < verticalblockcategoriesUpdate::version(verticalblockcategoriesUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version))) {
                        return "<span style='color:red; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('New version available!') . "</span>";
                    } else {
                        return "<span style='color:green; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('Module is up to date!') . "</span>";
                    }
                }
            }
        }
    }

    public function inconsistency($ret)
    {
        return;
    }

    public static function psversion($part = 1)
    {
        $version = _PS_VERSION_;
        $exp = explode('.', $version);
        if ($part == 0) {
            return $exp[0];
        }
        if ($part == 1) {
            if ($exp[0] >= 8) {
                return 7;
            }
            return $exp[1];
        }
        if ($part == 2) {
            return $exp[2];
        }
        if ($part == 3) {
            return $exp[3];
        }
    }


    public function install()
    {
        if (!parent::install() ||
            !$this->registerHook('displayHeader') ||
            !$this->registerHook('displayLeftColumn') || // Temporary hooks. Do NOT hook any module on it. Some CRUD hook will replace them as soon as possible.
            !$this->registerHook('displayLeftColumnProduct') || // Temporary hooks. Do NOT hook any module on it. Some CRUD hook will replace them as soon as possible.
            !$this->registerHook('categoryAddition') ||
            !$this->registerHook('actionCategoryAdd') ||
            !$this->registerHook('actionCategoryUpdate') ||
            !$this->registerHook('actionCategoryDelete') ||
            !$this->registerHook('actionAdminMetaControllerUpdate_optionsBefore') ||
            !Configuration::updateValue('BLOCK_CATEG_MAX_DEPTH', 4) ||
            !Configuration::updateValue('mypresta_updates', 1) ||
            !Configuration::updateValue('BLOCK_CATEG_DHTML', 1) ||
            !Configuration::updateValue('BLOCK_CATEG_ROOT_CATEGORY', 1)
        ) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }
        return true;
    }

    public function getContent()
    {
        $output = '';
        if (Tools::isSubmit('submitBlockCategories')) {
            $maxDepth = (int)(Tools::getValue('BLOCK_CATEG_MAX_DEPTH'));
            $dhtml = Tools::getValue('BLOCK_CATEG_DHTML');
            $nbrColumns = Tools::getValue('BLOCK_CATEG_NBR_COLUMN_FOOTER', 4);
            if ($maxDepth < 0) {
                $output .= $this->displayError($this->l('Maximum depth: Invalid number.'));
            } elseif ($dhtml != 0 && $dhtml != 1) {
                $output .= $this->displayError($this->l('Dynamic HTML: Invalid choice.'));
            } else {
                Configuration::updateValue('VC_IMG', Tools::getValue('VC_IMG'));
                Configuration::updateValue('BLOCK_CATEG_MAX_DEPTH', (int)$maxDepth);
                Configuration::updateValue('BLOCK_CATEG_DHTML', (int)$dhtml);
                Configuration::updateValue('BLOCK_CATEG_NBR_COLUMN_FOOTER', (int)$nbrColumns);
                Configuration::updateValue('BLOCK_CATEG_SORT_WAY', Tools::getValue('BLOCK_CATEG_SORT_WAY'));
                Configuration::updateValue('BLOCK_CATEG_SORT', Tools::getValue('BLOCK_CATEG_SORT'));
                Configuration::updateValue('BLOCK_CATEG_ROOT_CATEGORY', Tools::getValue('BLOCK_CATEG_ROOT_CATEGORY'));

                $this->_clearBlockcategoriesCache();

                Tools::redirectAdmin(AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&conf=6');
            }
        }
        return $output . $this->mypresta_add() . $this->renderForm() . $this->checkforupdates(0, 1);
    }

    public function mypresta_add()
    {
        return '
        <div class="panel">
            <h3> <i class="icon-usd"></i> ' . $this->l('MyPresta Modules') . '</h3>
            <div class="alert alert-success">
                <strong>' . $this->l('We develop this module for free.') . '</strong>
                ' . $this->l('If you want, you can ') . '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7WE8PTH4ZPYZA">' . $this->l('send PayPal donation') . '</a>
            </div>
            <div style="diplay:block; clear:both; margin-bottom:20px;">
              <iframe src="//apps.facepages.eu/somestuff/onlyexample.html" width="100%" height="150" border="0" style="border:none;"></iframe>
            </div>
		</div>
        <a style="display:block; border-radius:5px; text-align:center; padding:10px; background:#FFF; margin-bottom:10px; border:1px solid #cecece;" href="https://mypresta.eu/modules/front-office-features/multiple-categories-blocks.html" target="_blank">' . $this->l('Check module to build unlimited number of category blocks') . '</a>
        ';
    }

    public function getTree($resultParents, $resultIds, $maxDepth, $id_category = null, $currentDepth = 0)
    {
        if (is_null($id_category)) {
            $id_category = $this->context->shop->getCategory();
        }
        $children = array();
        if (isset($resultParents[$id_category]) && count($resultParents[$id_category]) && ($maxDepth == 0 || $currentDepth < $maxDepth)) {
            foreach ($resultParents[$id_category] as $subcat) {
                $children[] = $this->getTree($resultParents, $resultIds, $maxDepth, $subcat['id_category'], $currentDepth + 1);
            }
        }

        if ($this->imageFiles === null) {
            $this->imageFiles = scandir(_PS_CAT_IMG_DIR_);
        }

        if (isset($resultIds[$id_category])) {
            $link = $this->context->link->getCategoryLink($id_category, $resultIds[$id_category]['link_rewrite']);
            $name = $resultIds[$id_category]['name'];
            $desc = $resultIds[$id_category]['description'];
            $image_url = '';
            if (count(preg_grep('/^' . $resultIds[$id_category]["id_category"] . '-([0-9])?_thumb.jpg/i', $this->imageFiles)) > 0) {
                foreach ($this->imageFiles as $file) {
                    if (preg_match('/^' . $resultIds[$id_category]["id_category"] . '-([0-9])?_thumb.jpg/i', $file) === 1) {
                        $image_url = $this->context->link->getMediaLink(_THEME_CAT_DIR_ . $file);
                    }
                }
            }
        } else {
            $link = $name = $desc = $image_url = '';
        }

        $return = array(
            'id' => $id_category,
            'link' => $link,
            'name' => $name,
            'image' => $image_url,
            'desc' => $desc,
            'children' => $children
        );
        return $return;
    }

    public function hookdisplayLeftColumnProduct($params)
    {
        return $this->hookdisplayLeftColumn($params);
    }

    public function hookdisplayLeftColumn($params)
    {
        $this->setLastVisitedCategory();
        $phpself = $this->context->controller->php_self;
        $current_allowed_controllers = array('category');

        if ($phpself != null && in_array($phpself, $current_allowed_controllers) && Configuration::get('BLOCK_CATEG_ROOT_CATEGORY') && isset($this->context->cookie->last_visited_category) && $this->context->cookie->last_visited_category) {
            $category = new Category($this->context->cookie->last_visited_category, $this->context->language->id);
            if (Configuration::get('BLOCK_CATEG_ROOT_CATEGORY') == 2 && !$category->is_root_category && $category->id_parent) {
                $category = new Category($category->id_parent, $this->context->language->id);
            } elseif (Configuration::get('BLOCK_CATEG_ROOT_CATEGORY') == 3 && !$category->is_root_category && !$category->getSubCategories($category->id, true)) {
                $category = new Category($category->id_parent, $this->context->language->id);
            }
        } else {
            $category = new Category((int)Configuration::get('PS_HOME_CATEGORY'), $this->context->language->id);
        }

        $cacheId = $this->getCacheId($category ? $category->id : null);

        if (!$this->isCached('blockcategories.tpl', $cacheId)) {
            $range = '';
            $maxdepth = Configuration::get('BLOCK_CATEG_MAX_DEPTH');
            if (Validate::isLoadedObject($category)) {
                if ($maxdepth > 0) {
                    $maxdepth += $category->level_depth;
                }
                $range = 'AND nleft >= ' . (int)$category->nleft . ' AND nright <= ' . (int)$category->nright;
            }

            $resultIds = array();
            $resultParents = array();
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
				WHERE `id_group` IN (' . pSQL(implode(', ', Customer::getGroupsStatic((int)$this->context->customer->id))) . ')
			)
			ORDER BY `level_depth` ASC, ' . (Configuration::get('BLOCK_CATEG_SORT') ? 'cl.`name`' : 'cs.`position`') . ' ' . (Configuration::get('BLOCK_CATEG_SORT_WAY') ? 'DESC' : 'ASC'));
            foreach ($result as &$row) {
                $resultParents[$row['id_parent']][] = &$row;
                $resultIds[$row['id_category']] = &$row;
            }

            $blockCategTree = $this->getTree($resultParents, $resultIds, $maxdepth, ($category ? $category->id : null));
            $this->smarty->assign('blockCategTree', $blockCategTree);

            if ((Tools::getValue('id_product') || Tools::getValue('id_category')) && isset($this->context->cookie->last_visited_category) && $this->context->cookie->last_visited_category) {
                $category = new Category($this->context->cookie->last_visited_category, $this->context->language->id);
                if (Validate::isLoadedObject($category)) {
                    $this->smarty->assign(array(
                        'currentCategory' => $category,
                        'currentCategoryId' => $category->id
                    ));
                }
            }

            $defaultCategory = $this->returnDefaultCategory();
            $this->smarty->assign('isDhtml', Configuration::get('BLOCK_CATEG_DHTML'));
            $this->smarty->assign('ProductPageMainCategory', $defaultCategory);
            $this->smarty->assign('ProductPageMainCategoryParents', $this->returnDefaultCategoryParents($defaultCategory));

            if (file_exists(_PS_THEME_DIR_ . 'modules/verticalblockcategories/blockcategories.tpl')) {
                $this->smarty->assign('branche_tpl_path', _PS_THEME_DIR_ . 'modules/verticalblockcategories/category-tree-branch.tpl');
            } else {
                $this->smarty->assign('branche_tpl_path', _PS_MODULE_DIR_ . 'verticalblockcategories/category-tree-branch.tpl');
            }
        }
        return $this->display(__FILE__, 'blockcategories.tpl', $cacheId);
    }

    public function returnDefaultCategoryParents($id_category = false)
    {
        $array = array();
        if ($id_category == false) {
            return false;
        }
        $category = new Category($id_category);
        foreach ($category->getAllParents() as $category) {
            $array[] = $category->id_category;
        }
        if (count($array) > 0) {
            return $array;
        } else {
            return false;
        }
    }

    public function returnDefaultCategory()
    {
        if (Tools::getValue('id_product', 'false') != 'false') {
            if (Tools::getValue('controller') == 'product') {
                $product = new Product(Tools::getValue('id_product'), false, $this->context->language->id);
                return (isset($product->id_category_default) ? (is_int((int)$product->id_category_default) ? $product->id_category_default : false) : false);
            }
        }
        return false;
    }

    protected function getCacheId($name = null)
    {
        $cache_id = parent::getCacheId();

        if ($name !== null) {
            $cache_id .= '|' . $name;
        }

        if ((Tools::getValue('id_product') || Tools::getValue('id_category')) && isset($this->context->cookie->last_visited_category) && $this->context->cookie->last_visited_category) {
            $cache_id .= '|' . (int)$this->context->cookie->last_visited_category;
        }

        return $cache_id . '|' . implode('-', Customer::getGroupsStatic($this->context->customer->id));
    }

    public function setLastVisitedCategory()
    {
        $cache_id = 'verticalblockcategories::setLastVisitedCategory';
        if (!Cache::isStored($cache_id)) {
            if (method_exists($this->context->controller, 'getCategory') && ($category = $this->context->controller->getCategory())) {
                $this->context->cookie->last_visited_category = $category->id;
            } elseif (method_exists($this->context->controller, 'getProduct') && ($product = $this->context->controller->getProduct())) {
                if (!isset($this->context->cookie->last_visited_category) || !Product::idIsOnCategoryId($product->id, array(array('id_category' => $this->context->cookie->last_visited_category))) || !Category::inShopStatic($this->context->cookie->last_visited_category, $this->context->shop)) {
                    $this->context->cookie->last_visited_category = (int)$product->id_category_default;
                }
            }
            Cache::store($cache_id, $this->context->cookie->last_visited_category);
        }
        return Cache::retrieve($cache_id);
    }

    public function hookdisplayRightColumn($params)
    {
        return $this->hookLeftColumn($params);
    }

    public function hookdisplayHeader()
    {
        $this->context->controller->addCSS(($this->_path) . 'blockcategories.css', 'all');
    }

    private function _clearBlockcategoriesCache()
    {
        $this->_clearCache('blockcategories.tpl');
        $this->_clearCache('blockcategories_footer.tpl');
    }

    public function hookCategoryAddition($params)
    {
        $this->_clearBlockcategoriesCache();
    }

    public function hookactionCategoryAdd($params)
    {
        $this->_clearBlockcategoriesCache();
    }

    public function hookCategoryUpdate($params)
    {
        $this->_clearBlockcategoriesCache();
    }

    public function hookactionCategoryUpdate($params)
    {
        $this->_clearBlockcategoriesCache();
    }

    public function hookCategoryDeletion($params)
    {
        $this->_clearBlockcategoriesCache();
    }

    public function hookactionCategoryDelete($params)
    {
        $this->_clearBlockcategoriesCache();
    }

    public function hookActionAdminMetaControllerUpdate_optionsBefore($params)
    {
        $this->_clearBlockcategoriesCache();
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
                        'type' => 'radio',
                        'label' => $this->l('Category root'),
                        'name' => 'BLOCK_CATEG_ROOT_CATEGORY',
                        'hint' => $this->l('Select which category is displayed in the block. The current category is the one the visitor is currently browsing.'),
                        'values' => array(
                            array(
                                'id' => 'home',
                                'value' => 0,
                                'label' => $this->l('Home category')
                            ),
                            array(
                                'id' => 'current',
                                'value' => 1,
                                'label' => $this->l('Current category')
                            ),
                            array(
                                'id' => 'parent',
                                'value' => 2,
                                'label' => $this->l('Parent category')
                            ),
                            array(
                                'id' => 'current_parent',
                                'value' => 3,
                                'label' => $this->l('Current category, unless it has no subcategories, in which case the parent category of the current category is used')
                            ),
                        )
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Maximum depth'),
                        'name' => 'BLOCK_CATEG_MAX_DEPTH',
                        'desc' => $this->l('Set the maximum depth of category sublevels displayed in this block (0 = infinite).'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Dynamic'),
                        'name' => 'BLOCK_CATEG_DHTML',
                        'desc' => $this->l('Activate dynamic (animated) mode for category sublevels.'),
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
                        'label' => $this->l('Show menu picture thumb'),
                        'name' => 'VC_IMG',
                        'desc' => $this->l('If category will be associated with menu thumbnail - it can appear in the menu near the category name'),
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
                        'type' => 'radio',
                        'label' => $this->l('Sort'),
                        'name' => 'BLOCK_CATEG_SORT',
                        'values' => array(
                            array(
                                'id' => 'name',
                                'value' => 1,
                                'label' => $this->l('By name')
                            ),
                            array(
                                'id' => 'position',
                                'value' => 0,
                                'label' => $this->l('By position')
                            ),
                        )
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->l('Sort order'),
                        'name' => 'BLOCK_CATEG_SORT_WAY',
                        'values' => array(
                            array(
                                'id' => 'name',
                                'value' => 1,
                                'label' => $this->l('Descending')
                            ),
                            array(
                                'id' => 'position',
                                'value' => 0,
                                'label' => $this->l('Ascending')
                            ),
                        )
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitBlockCategories';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
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
            'VC_IMG' => Tools::getValue('VC_IMG', Configuration::get('VC_IMG')),
            'BLOCK_CATEG_MAX_DEPTH' => Tools::getValue('BLOCK_CATEG_MAX_DEPTH', Configuration::get('BLOCK_CATEG_MAX_DEPTH')),
            'BLOCK_CATEG_DHTML' => Tools::getValue('BLOCK_CATEG_DHTML', Configuration::get('BLOCK_CATEG_DHTML')),
            'BLOCK_CATEG_SORT_WAY' => Tools::getValue('BLOCK_CATEG_SORT_WAY', Configuration::get('BLOCK_CATEG_SORT_WAY')),
            'BLOCK_CATEG_SORT' => Tools::getValue('BLOCK_CATEG_SORT', Configuration::get('BLOCK_CATEG_SORT')),
            'BLOCK_CATEG_ROOT_CATEGORY' => Tools::getValue('BLOCK_CATEG_ROOT_CATEGORY', Configuration::get('BLOCK_CATEG_ROOT_CATEGORY'))
        );
    }
}


class verticalblockcategoriesUpdate extends verticalblockcategories
{
    public static function version($version)
    {
        $version = (int)str_replace(".", "", $version);
        if (strlen($version) == 3) {
            $version = (int)$version . "0";
        }
        if (strlen($version) == 2) {
            $version = (int)$version . "00";
        }
        if (strlen($version) == 1) {
            $version = (int)$version . "000";
        }
        if (strlen($version) == 0) {
            $version = (int)$version . "0000";
        }
        return (int)$version;
    }

    public static function encrypt($string)
    {
        return base64_encode($string);
    }

    public static function verify($module, $key, $version)
    {
        if (ini_get("allow_url_fopen")) {
            if (function_exists("file_get_contents")) {
                $actual_version = @file_get_contents('http://dev.mypresta.eu/update/get.php?module=' . $module . "&version=" . self::encrypt($version) . "&lic=$key&u=" . self::encrypt(_PS_BASE_URL_ . __PS_BASE_URI__));
            }
        }
        Configuration::updateValue("update_" . $module, date("U"));
        Configuration::updateValue("updatev_" . $module, $actual_version);
        return $actual_version;
    }
}

