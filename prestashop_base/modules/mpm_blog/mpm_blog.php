<?php
/**
 * Created by PhpStorm.
 * User: maskc_000
 * Date: 08.11.13
 * Time: 10:59
 */

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class mpm_blog extends Module implements WidgetInterface
{
    private $_model;
    private $_shopId;
    private $_langId;
    private $_defaultSettings;
    private $_emptySettings;
    private $_html;

    public function __construct()
    {
        require_once(dirname(__FILE__) . '/classes/blogPost.php');
        require_once(dirname(__FILE__) . '/classes/blogCategory.php');
        $this->_objPost = new blogPost();
        $this->_shopId = Context::getContext()->shop->id;
        $this->_langId = Context::getContext()->language->id;
        $this->name = 'mpm_blog';
        $this->tab = 'front_office_features';
        $this->version = '3.0.5';
        $this->author = 'MyPrestaModules';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = ['min' => '1.6', 'max' => _PS_VERSION_];
        $this->bootstrap = true;
        $this->module_key = "2ad61ac5e5f741256ba95b3cca8211ba";
        parent::__construct(); // The parent construct is required for translations
        $this->displayName = $this->l('Functional Blog');
        $this->description = $this->l('Look for the best ways about your customer comfort to offer them different possibilities such as posting and sharing the latest news and articles.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
        $this->_defaultSettings = [
            'count_post'                     => '8',
            'show_archive'                   => 1,
            'show_social_button'             => 1,
            'articles_home_page_shop'        => 1,
            'articles_footer'                => 1,
            'button_facebook'                => 1,
            'button_twitter'                 => 1,
            'button_googleplus'              => 1,
            'button_linkedin'                => 1,
            'button_email'                   => 1,
            'button_pinterest'               => 1,
            'button_pocket'                  => 1,
            'button_tumblr'                  => 1,
            'button_reddit'                  => 1,
            'button_hackernews'              => 1,
            'show_archive_home'              => 1,
            'show_categories'                => 1,
            'show_categories_home'           => 1,
            'show_tags'                      => 1,
            'show_tags_home'                 => 1,
            'show_article_product_page'      => 1,
            'hook'                           => 'displayHomeContent4',
            'active_index'                   => 0,
            'description_index'              => '',
            'show_search'                    => 1,
            'show_search_home'               => 1,
            'using_captcha'                  => 0,
            'use_comments'                   => 1,
            'validate_comments'              => 1,
            'unregistered_users'             => 1,
            'new_comments'                   => 1,
            'send_email'                     => 'demo@demo.com',
            'related_products_description'   => 1,
            'number_related_products'        => '3',
            'number_articles_footer'         => '5',
            'number_articles_home_page_shop' => '4',
            'featured_posts'        => 1,
            'featured_posts_home'   => 0,
            'number_featured_posts' => '2',
            'image_list_height'     => '608',
            'image_list_width'      => '900',
            'image_grid_height'     => '420',
            'image_grid_width'      => '621',
            'image_featured_height' => '190',
            'image_featured_width'  => '280',
            'image_home_height'     => '271',
            'image_home_width'      => '401',
        ];
        $this->_emptySettings = [
            'count_post'                     => ' ',
            'hook'                           => ' ',
            'show_archive'                   => 0,
            'show_social_button'             => 0,
            'articles_home_page_shop'        => 0,
            'number_articles_home_page_shop' => 0,
            'number_articles_footer'         => 0,
            'articles_footer'                => 0,
            'button_facebook'                => 0,
            'button_twitter'                 => 0,
            'button_googleplus'              => 0,
            'button_linkedin'                => 0,
            'button_email'                   => 0,
            'button_pinterest'               => 0,
            'button_pocket'                  => 0,
            'button_tumblr'                  => 0,
            'button_reddit'                  => 0,
            'button_hackernews'              => 0,
            'show_archive_home'              => 0,
            'show_categories'                => 0,
            'show_categories_home'           => 0,
            'show_tags'                      => 0,
            'show_tags_home'                 => 0,
            'show_article_product_page'      => 0,
            'description_index'              => '',
            'active_index'                   => 0,
            'show_search'                    => 0,
            'show_search_home'               => 0,
            'using_captcha'                  => 0,
            'use_comments'                   => 1,
            'validate_comments'              => 0,
            'unregistered_users'             => 0,
            'new_comments'                   => 0,
            'send_email'                     => ' ',
            'related_products_description'   => 0,
            'number_related_products'        => ' ',
            'featured_posts'                 => 0,
            'featured_posts_home'            => 0,
            'number_featured_posts'          => ' ',
            'image_list_height'              => '',
            'image_list_width'               => '',
            'image_grid_height'              => '',
            'image_grid_width'               => '',
            'image_home_height'              => '',
            'image_home_width'               => '',
            'image_featured_height'          => '',
            'image_featured_width'           => '',
        ];
    }

    public function install()
    {
        if (!parent::install()
            || !Configuration::updateValue('GOMAKOIL_FUNCTIONAL_BLOG', '')
            || !$this->registerHook('actionAdminControllerSetMedia')
            || !$this->registerHook('moduleRoutes')
            || !$this->registerHook('displayBackOfficeHeader')
            || !$this->registerHook('displayProductExtraContent')
            || !$this->registerHook('displayLeftColumn')
            || !$this->registerHook('displayHeader')
            || !$this->registerHook('displayHome')
            || !$this->registerHook('footer')
            || !$this->registerhook('displayHomeContent1')
            || !$this->registerhook('displayHomeContent2')
            || !$this->registerhook('displayHomeContent3')
            || !$this->registerhook('displayHomeContent4')
            || !$this->registerhook('displayHomeContent5')
        ) {
            return false;
        }
        if (!$this->existsTab('AdminBlog')) {
            if (!$this->addTab($this->l('Functional Blog'), 'AdminBlog', $this->getIdTabFromClassName('CONFIGURE'),
                0)) {
                return false;
            } else {
                $id_tab = (int)$this->getIdTabFromClassName('AdminBlog');
                Db::getInstance(_PS_USE_SQL_SLAVE_)->update("tab", ['icon' => 'description'], "id_tab = $id_tab");
            }
        }
        if (!$this->existsTab('AdminCategoryBlog')) {
            if (!$this->addTab($this->l('Categories'), 'AdminCategoryBlog', $this->getIdTabFromClassName('AdminBlog'),
                1)) {
                return false;
            } else {
                $id_tab = (int)$this->getIdTabFromClassName('AdminCategoryBlog');
                Db::getInstance(_PS_USE_SQL_SLAVE_)->update("tab", ['icon' => 'description'], "id_tab = $id_tab");
            }
        }
        if (!$this->existsTab('AdminPostBlog')) {
            if (!$this->addTab($this->l('Articles'), 'AdminPostBlog', $this->getIdTabFromClassName('AdminBlog'), 2)) {
                return false;
            } else {
                $id_tab = (int)$this->getIdTabFromClassName('AdminPostBlog');
                Db::getInstance(_PS_USE_SQL_SLAVE_)->update("tab", ['icon' => 'description'], "id_tab = $id_tab");
            }
        }
        if (!$this->existsTab('AdminCommentsBlog')) {
            if (!$this->addTab($this->l('Comments'), 'AdminCommentsBlog', $this->getIdTabFromClassName('AdminBlog'),
                3)) {
                return false;
            } else {
                $id_tab = (int)$this->getIdTabFromClassName('AdminCommentsBlog');
                Db::getInstance(_PS_USE_SQL_SLAVE_)->update("tab", ['icon' => 'comment'], "id_tab = $id_tab");
            }
        }
        if (!$this->existsTab('AdminSettingsBlog')) {
            if (!$this->addTab($this->l('Settings'), 'AdminSettingsBlog', $this->getIdTabFromClassName('AdminBlog'),
                4)) {
                return false;
            } else {
                $id_tab = (int)$this->getIdTabFromClassName('AdminSettingsBlog');
                Db::getInstance(_PS_USE_SQL_SLAVE_)->update("tab", ['icon' => 'settings_applications'],
                    "id_tab = $id_tab");
            }
        }
        if (!$this->installDb()) {
            return false;
        }
        foreach (Shop::getContextListShopID() as $id_shop) {
            $this->installConfiguration($id_shop);
        }
        foreach (Language::getLanguages(false) as $lang) {
            $res = [
                'description'      => ' ',
                'meta_title'       => 'Blog',
                'meta_keywords'    => 'Blog',
                'meta_description' => 'Blog',
                'id_lang'          => $lang['id_lang'],
            ];
            Db::getInstance(_PS_USE_SQL_SLAVE_)->insert("blog_index_page_lang", $res);
        }
        $meta = new Meta();
        $pages = $meta->getPages();
        if (!isset($pages['mpm_blog - display']) || !$pages['mpm_blog - display']) {
            $meta->page = 'module-mpm_blog-display';
            $meta->configurable = 1;
            $meta->save();
        }
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()
            || !Configuration::deleteByName('GOMAKOIL_FUNCTIONAL_BLOG')
        ) {
            return false;
        }
        if (!$this->removeTab('AdminBlog')) {
            return false;
        }
        if (!$this->removeTab('AdminCategoryBlog')) {
            return false;
        }
        if (!$this->removeTab('AdminPostBlog')) {
            return false;
        }
        if (!$this->removeTab('AdminCommentsBlog')) {
            return false;
        }
        if (!$this->removeTab('AdminSettingsBlog')) {
            return false;
        }
        if (!$this->uninstallDb()) {
            return false;
        }
        $this->_deleteImageIndex();
        return true;
    }

    public function hookActionAdminControllerSetMedia()
    {
        if (Tools::getValue('controller') != 'AdminPostBlog') {
            return false;
        }

        $this->context->controller->addjQueryPlugin([
            'tagify',
        ]);
    }

    public function hookModuleRoutes($params)
    {
        return [
            'display-blog-cat'       => [
                'controller' => 'display',
                'rule'       => 'blog{/:category}',
                'keywords'   => [
                    'category'      => [
                        'regexp' => '[_a-zA-Z0-9_-]*',
                        'param'  => 'category',
                    ],
                    'meta_keywords' => ['regexp' => '[_a-zA-Z0-9-\pL]*'],
                    'meta_title'    => ['regexp' => '[_a-zA-Z0-9-\pL]*'],
                ],
                'params'     => [
                    'fc'     => 'module',
                    'module' => 'mpm_blog'
                ]
            ],
            'display-blog-cat-p'     => [
                'controller' => 'display',
                'rule'       => 'blog{/:category}/p{/:p}',
                'keywords'   => [
                    'category'      => [
                        'regexp' => '[_a-zA-Z0-9_-]*',
                        'param'  => 'category',
                    ],
                    'p'             => [
                        'regexp' => '[0-9]*',
                        'param'  => 'p',
                    ],
                    'meta_keywords' => ['regexp' => '[_a-zA-Z0-9-\pL]*'],
                    'meta_title'    => ['regexp' => '[_a-zA-Z0-9-\pL]*'],
                ],
                'params'     => [
                    'fc'     => 'module',
                    'module' => 'mpm_blog'
                ]
            ],
            'display-blog-article'   => [
                'controller' => 'display',
                'rule'       => 'blog{/:category}{/:article}.html',
                'keywords'   => [
                    'category'      => [
                        'regexp' => '[_a-zA-Z0-9_-]*',
                        'param'  => 'category',
                    ],
                    'article'       => [
                        'regexp' => '[_a-zA-Z0-9_-]*',
                        'param'  => 'article',
                    ],
                    'meta_keywords' => ['regexp' => '[_a-zA-Z0-9-\pL]*'],
                    'meta_title'    => ['regexp' => '[_a-zA-Z0-9-\pL]*'],
                ],
                'params'     => [
                    'fc'     => 'module',
                    'module' => 'mpm_blog'
                ]
            ],
            'display-blog-search'    => [
                'controller' => 'display',
                'rule'       => 'blog/search{/:search}',
                'keywords'   => [
                    'search'        => [
                        'regexp' => '.*',
                        'param'  => 'search',
                    ],
                    'meta_keywords' => ['regexp' => '[_a-zA-Z0-9-\pL]*'],
                    'meta_title'    => ['regexp' => '[_a-zA-Z0-9-\pL]*'],
                ],
                'params'     => [
                    'fc'     => 'module',
                    'module' => 'mpm_blog'
                ]
            ],
            'display-blog-search-p'  => [
                'controller' => 'display',
                'rule'       => 'blog/search{/:search}/p{/:p}',
                'keywords'   => [
                    'search'        => [
                        'regexp' => '[_a-zA-Z0-9_-]*',
                        'param'  => 'search',
                    ],
                    'p'             => [
                        'regexp' => '[0-9]*',
                        'param'  => 'p',
                    ],
                    'meta_keywords' => ['regexp' => '[_a-zA-Z0-9-\pL]*'],
                    'meta_title'    => ['regexp' => '[_a-zA-Z0-9-\pL]*'],
                ],
                'params'     => [
                    'fc'     => 'module',
                    'module' => 'mpm_blog'
                ]
            ],
            'display-blog-archive'   => [
                'controller' => 'display',
                'rule'       => 'blog/archive{/:archive}',
                'keywords'   => [
                    'archive'       => [
                        'regexp' => '[_a-zA-Z0-9_-]*',
                        'param'  => 'archive',
                    ],
                    'meta_keywords' => ['regexp' => '[_a-zA-Z0-9-\pL]*'],
                    'meta_title'    => ['regexp' => '[_a-zA-Z0-9-\pL]*'],
                ],
                'params'     => [
                    'fc'     => 'module',
                    'module' => 'mpm_blog'
                ]
            ],
            'display-blog-archive-p' => [
                'controller' => 'display',
                'rule'       => 'blog/archive{/:archive}/p{/:p}',
                'keywords'   => [
                    'archive'       => [
                        'regexp' => '[_a-zA-Z0-9_-]*',
                        'param'  => 'archive',
                    ],
                    'p'             => [
                        'regexp' => '[0-9]*',
                        'param'  => 'p',
                    ],
                    'meta_keywords' => ['regexp' => '[_a-zA-Z0-9-\pL]*'],
                    'meta_title'    => ['regexp' => '[_a-zA-Z0-9-\pL]*'],
                ],
                'params'     => [
                    'fc'     => 'module',
                    'module' => 'mpm_blog'
                ]
            ],
            'display-faq-home'       => [
                'controller' => 'display',
                'rule'       => 'blog/',
                'keywords'   => [
                    'meta_keywords' => ['regexp' => '[_a-zA-Z0-9-\pL]*'],
                    'meta_title'    => ['regexp' => '[_a-zA-Z0-9-\pL]*'],
                ],
                'params'     => [
                    'fc'     => 'module',
                    'module' => 'mpm_blog'
                ]
            ],
            'display-faq-home-p'     => [
                'controller' => 'display',
                'rule'       => 'blog/p{/:p}',
                'keywords'   => [
                    'meta_keywords' => ['regexp' => '[_a-zA-Z0-9-\pL]*'],
                    'meta_title'    => ['regexp' => '[_a-zA-Z0-9-\pL]*'],
                ],
                'p'          => [
                    'regexp' => '[0-9]*',
                    'param'  => 'p',
                ],
                'params'     => [
                    'fc'     => 'module',
                    'module' => 'mpm_blog'
                ]
            ],
            'display-faq-home2'      => [
                'controller' => 'display',
                'rule'       => 'blog',
                'keywords'   => [
                    'meta_keywords' => ['regexp' => '[_a-zA-Z0-9-\pL]*'],
                    'meta_title'    => ['regexp' => '[_a-zA-Z0-9-\pL]*'],
                ],
                'params'     => [
                    'fc'     => 'module',
                    'module' => 'mpm_blog'
                ]
            ],
        ];
    }

    public function existsTab($tabClass)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
		SELECT id_tab AS id
		FROM `' . _DB_PREFIX_ . 'tab` t
		WHERE LOWER(t.`class_name`) = \'' . pSQL($tabClass) . '\'');
        if (count($result) == 0) {
            return false;
        }
        return true;
    }

    private function addTab($tabName, $tabClass, $id_parent, $position)
    {
        $tab = new Tab();
        $langs = Language::getLanguages();
        foreach ($langs as $lang) {
            $tab->name[$lang['id_lang']] = $tabName;
        }
        $tab->class_name = $tabClass;
        $tab->module = $this->name;
        $tab->id_parent = $id_parent;
        $tab->position = $position;
        if (!$tab->save()) {
            return false;
        }
        return true;
    }

    public function getIdTabFromClassName($tabName)
    {
        $sql = 'SELECT id_tab FROM ' . _DB_PREFIX_ . 'tab WHERE class_name="' . $tabName . '"';
        $tab = Db::getInstance()->getRow($sql);
        return (int)$tab['id_tab'];
    }

    private function removeTab($tabClass)
    {
        $idTab = Tab::getIdFromClassName($tabClass);
        if ($idTab != 0) {
            $tab = new Tab($idTab);
            $tab->delete();
            return true;
        }
        return false;
    }

    public function installDb()
    {
        // Table category
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'blog_category';
        Db::getInstance()->execute($sql);
        $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'blog_category(
				id_blog_category int(11) NOT NULL AUTO_INCREMENT,
				active boolean NULL,
				position int(11) NULL,
				allow_comment bool NULL,
				date_add datetime NULL,
				PRIMARY KEY (`id_blog_category`)
				)
				DEFAULT CHARACTER SET = utf8
				';
        Db::getInstance()->execute($sql);
        // Table category_lang
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'blog_category_lang';
        Db::getInstance()->execute($sql);
        $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'blog_category_lang(
				id_blog_category int(11) NOT NULL AUTO_INCREMENT,
				id_lang int(11) NOT NULL,
				id_shop int(10) unsigned NOT NULL,
				name nvarchar(500) NOT NULL,
				description nvarchar(2000) NULL,
				meta_title nvarchar(500) NULL,
				meta_description nvarchar(1000) NULL,
				meta_keywords nvarchar(1000) NULL,
				link_rewrite nvarchar(1000) NOT NULL,
				PRIMARY KEY(id_blog_category,id_shop, id_lang)
				)
				DEFAULT CHARACTER SET = utf8
				';
        Db::getInstance()->execute($sql);
        // Table post
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'blog_post';
        Db::getInstance()->execute($sql);
        $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'blog_post(
				id_blog_post int(11) NOT NULL AUTO_INCREMENT,
				id_blog_category int(11) NOT NULL,
				position int(11) NULL,
				allow_comment boolean NULL,
				show_in_most boolean NULL,
				active boolean NULL,
				id_related_posts varchar(255) NULL,
				id_related_products varchar(255) NULL,
				date_add datetime NULL,
				PRIMARY KEY(id_blog_post)
				)
				DEFAULT CHARACTER SET = utf8
				';
        Db::getInstance()->execute($sql);
        // Table post_lang
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'blog_post_lang';
        Db::getInstance()->execute($sql);
        $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'blog_post_lang(
				id_blog_post int(11) NOT NULL AUTO_INCREMENT,
				id_lang int(11) NOT NULL,
				id_shop int(11) NOT NULL,
				name nvarchar(2000) NOT NULL,
				description_short text NULL,
				description text NULL,
				meta_title nvarchar(500) NULL,
				meta_description nvarchar(1000) NULL,
				meta_keywords nvarchar(1000) NULL,
				tags nvarchar(2000) NULL,
				link_rewrite nvarchar(1000) NOT NULL,
				PRIMARY KEY(id_blog_post,id_lang,id_shop)
				)
				DEFAULT CHARACTER SET = utf8
				';
        Db::getInstance()->execute($sql);
        // Table comment
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'blog_comment';
        Db::getInstance()->execute($sql);
        $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'blog_comment(
				id_blog_comment int(11) NOT NULL AUTO_INCREMENT,
				id_blog_post int(11) NOT NULL,
				id_shop int(11) NOT NULL,
				active boolean NOT NULL,
				title nvarchar(500) NOT NULL,
				content text NOT NULL,
				author_name nvarchar(100) NULL,
				author_email nvarchar(100) NULL,
				rating int(11) NOT NULL,
				date_add datetime NULL,
				PRIMARY KEY(id_blog_comment)
				)
				DEFAULT CHARACTER SET = utf8
				';
        Db::getInstance()->execute($sql);
        // Table category_lang
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'blog_index_page';
        Db::getInstance()->execute($sql);
        $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'blog_index_page(
				id_blog_index_page int(11) NOT NULL AUTO_INCREMENT,
			
				PRIMARY KEY(id_blog_index_page)
				)
				DEFAULT CHARACTER SET = utf8
				';
        Db::getInstance()->execute($sql);
        // Table category_lang
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'blog_index_page_lang';
        Db::getInstance()->execute($sql);
        $sql = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'blog_index_page_lang(
				id_blog_index_page_lang int(11) NOT NULL AUTO_INCREMENT,
				id_lang int(11) NOT NULL,
				description nvarchar(2000) NULL,
				meta_title nvarchar(500) NULL,
				meta_description nvarchar(1000) NULL,
				meta_keywords nvarchar(1000) NULL,
		
				PRIMARY KEY(id_blog_index_page_lang, id_lang)
				)
				DEFAULT CHARACTER SET = utf8
				';
        Db::getInstance()->execute($sql);
        return true;
    }

    public function uninstallDb()
    {
//     delete table blog_category
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'blog_category';
        Db::getInstance()->execute($sql);
        // delete table blog_category_lang
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'blog_category_lang';
        Db::getInstance()->execute($sql);
//     delete table blog_post
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'blog_post';
        Db::getInstance()->execute($sql);
//     delete table blog_post_lang
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'blog_post_lang';
        Db::getInstance()->execute($sql);
        // delete table blog_comment
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'blog_comment';
        Db::getInstance()->execute($sql);    // delete table blog_comment
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'blog_index_page';
        Db::getInstance()->execute($sql);
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'blog_index_page_lang';
        Db::getInstance()->execute($sql);
        return true;
    }

    public function installConfiguration($id_shop = null)
    {
        $config = serialize($this->_defaultSettings);
        ConfigurationCore::updateValue('GOMAKOIL_FUNCTIONAL_BLOG', $config, true, null, $id_shop);
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') == 'mpm_blog' || Tools::getValue('controller') == 'AdminCategoryBlog'
            || Tools::getValue('controller') == 'AdminPostBlog' || Tools::getValue('controller') == 'AdminCommentsBlog'
        ) {
            $this->context->controller->addJquery();
            $this->context->controller->addJqueryPlugin('tablednd');
            $this->context->controller->addCss($this->_path . 'views/css/style.css', 'all');
            $this->context->controller->addJS($this->_path . 'views/js/blog_admin.js', 'all');
        }
        if (version_compare(_PS_VERSION_, '1.6.0.15') >= 0 && version_compare(_PS_VERSION_, '1.7.0.0') < 0) {
            $this->context->controller->addCss($this->_path . 'views/css/style_1610.css', 'all');
        }
    }

    public function hookdisplayHeader()
    {
        $settings = Tools::unserialize(Configuration::get('GOMAKOIL_FUNCTIONAL_BLOG'));
        $php_self = Context::getContext()->controller->php_self;
        if ($php_self == 'module-mpm_blog-display'
            || ($settings['show_search'] && !$settings['show_search_home'])
            || ($settings['show_archive'] && !$settings['show_archive_home'])
            || ($settings['show_categories'] && !$settings['show_categories_home'])
            || ($settings['show_tags'] && !$settings['show_tags_home'])
            || ($settings['featured_posts'] && !$settings['featured_posts_home'])) {
            $this->context->controller->registerStylesheet('blog_left', 'modules/mpm_blog/views/css/style.css',
                ['media' => 'all', 'priority' => 150]);
            $this->context->controller->registerStylesheet('blog_bxslider_left',
                'modules/mpm_blog/views/css/jquery.bxslider.css', ['media' => 'all', 'priority' => 150]);
            $this->context->controller->registerJavascript('blog_bxslider_js_left',
                'modules/mpm_blog/views/js/jquery.bxslider.js',
                ['media' => 'all', 'position' => 'bottom', 'priority' => 150]);
            $this->context->controller->registerJavascript('blog_left', 'modules/mpm_blog/views/js/blog.js',
                ['media' => 'all', 'position' => 'bottom', 'priority' => 150]);
        }
        if ($settings['articles_footer']) {
            $this->context->controller->registerStylesheet('blog_articles_footer',
                'modules/mpm_blog/views/css/footer_block.css', ['media' => 'all', 'priority' => 150]);
        }
        if ($settings['articles_home_page_shop']) {
            $this->context->controller->registerStylesheet('blog_articles_home',
                'modules/mpm_blog/views/css/homer_block.css', ['media' => 'all', 'priority' => 150]);
        }
    }

    public function hookdisplayLeftColumn()
    {
        return $this->getLeftColumnSettings();
    }

    public function getLeftColumnSettings()
    {
        $settings = Tools::unserialize(Configuration::get('GOMAKOIL_FUNCTIONAL_BLOG'));
        $php_self = Context::getContext()->controller->php_self;
        $featured = [];
        $archive = [];
        $blogCat = [];
        $most_tags = [];
        $blogUrl = _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'blog/';
        if ($settings['featured_posts']) {
            $featured = $this->_objPost->getPostFeatured($this->_langId, $this->_shopId);
            foreach ($featured as $key => $tmpPost) {
                $featured[$key]['is_image'] = false;
                if (file_exists(_PS_IMG_DIR_ . 'blog/' . date('Y-m',
                        strtotime($tmpPost['date_add'])) . '/' . $tmpPost['id_blog_post'] . '.jpg')) {
                    $featured[$key]['is_image'] = _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'img/blog/' . date('Y-m',
                            strtotime($tmpPost['date_add'])) . '/';
                }
            }
        }
        if ($settings['show_archive']) {
            $archive = $this->_objPost->getPostByMonth($this->_langId, $this->_shopId);
        }
        if ($settings['show_categories']) {
            $objCategory = new blogCategory();
            $blogCat = $objCategory->getCategories($this->_langId, $this->_shopId);
        }
        if ($settings['show_tags']) {
            $tags = $this->_objPost->getTags($this->_langId, $this->_shopId);
            $most_tags = [];
            foreach ($tags as $tag) {
                $tag = explode(',', $tag['tags']);
                if ($tag[0]) {
                    $most_tags[] = $tag[0];
                }
            }
        }
        if ($php_self !== 'module-mpm_blog-display') {
            if ($settings['show_search_home']) {
                $settings['show_search'] = false;
            }
            if ($settings['show_archive_home']) {
                $settings['show_archive'] = false;
            }
            if ($settings['show_categories_home']) {
                $settings['show_categories'] = false;
            }
            if ($settings['show_tags_home']) {
                $settings['show_tags'] = false;
            }
            if ($settings['featured_posts_home']) {
                $settings['featured_posts'] = false;
            }
        }
        $this->context->smarty->assign(
            [
                'blogCat'   => $blogCat,
                'archives'  => $archive,
                'featured'  => $featured,
                'blogUrl'   => $blogUrl,
                'most_tags' => $most_tags,
                'settings'  => $settings,
            ]
        );
        return $this->display(__FILE__, 'views/templates/front/left-column.tpl');
    }

    public function getContent()
    {
        $this->_postProcess();
        $this->displayForm();
        return $this->_html;
    }

    protected function uploadImage($name)
    {
        if (isset($_FILES[$name]['tmp_name']) && !empty($_FILES[$name]['tmp_name'])) {
            $max_size = isset($this->maxImageSize) ? $this->maxImageSize : 0;
            if ($error = ImageManager::validateUpload($_FILES[$name], Tools::getMaxUploadSize($max_size))) {
                $this->_errors[] = $error;
            } elseif (!($tmpName = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES[$name]['tmp_name'],
                    $tmpName)) {
                return false;
            } else {
                $_FILES[$name]['tmp_name'] = $tmpName;
                $type = Tools::strtolower(Tools::substr(strrchr($_FILES[$name]['name'], '.'), 1));
                if (!ImageManager::resize($tmpName, dirname(__FILE__) . '/views/img/index.' . $type, null, null,
                    $type)) {
                    $this->_errors[] = Tools::displayError('An error occurred while uploading image.');
                } else {
                    Configuration::updateValue('GOMAKOIL_FUNCTIONAL_BLOG_IMG', serialize($type));
                }
                unlink($tmpName);
            }
        }
        return true;
    }

    private function _deleteImageIndex()
    {
        $img = Tools::unSerialize(Configuration::get('GOMAKOIL_FUNCTIONAL_BLOG_IMG'));
        $path_img = dirname(__FILE__) . '/views/img/index.' . $img;
        if (file_exists($path_img)) {
            unlink($path_img);
            Configuration::updateValue('GOMAKOIL_FUNCTIONAL_BLOG_IMG', '');
        }
    }

    private function _postProcess()
    {
        if (Tools::isSubmit('deleteImageIndex')) {
            $this->_deleteImageIndex();
        }
        if (Tools::isSubmit('saveBlogSettings')) {
            $this->uploadImage('image_index');
            foreach (Language::getLanguages(false) as $lang) {
                $id = $this->getIdSettings($lang['id_lang']);
                $res = [
                    'description'      => pSQL(Tools::getValue('description_index_' . $lang['id_lang']), true),
                    'meta_title'       => pSQL(Tools::getValue('index_meta_title_' . $lang['id_lang'])),
                    'meta_description' => pSQL(Tools::getValue('index_meta_description_' . $lang['id_lang'])),
                    'meta_keywords'    => pSQL(Tools::getValue('index_meta_keywords_' . $lang['id_lang'])),
                ];
                if ($id) {
                    Db::getInstance(_PS_USE_SQL_SLAVE_)->update("blog_index_page_lang", $res,
                        "id_blog_index_page_lang = $id");
                } else {
                    $res['id_lang'] = $lang['id_lang'];
                    Db::getInstance(_PS_USE_SQL_SLAVE_)->insert("blog_index_page_lang", $res);
                }
            }
            $config = [
                'count_post'                     => Tools::getValue('count_post'),
                'show_archive'                   => Tools::getValue('show_archive'),
                'show_archive_home'              => Tools::getValue('show_archive_home'),
                'show_social_button'             => Tools::getValue('show_social_button'),
                'articles_footer'                => Tools::getValue('articles_footer'),
                'articles_home_page_shop'        => Tools::getValue('articles_home_page_shop'),
                'number_articles_home_page_shop' => Tools::getValue('number_articles_home_page_shop'),
                'number_articles_footer'         => Tools::getValue('number_articles_footer'),
                'show_categories'                => Tools::getValue('show_categories'),
                'show_categories_home'           => Tools::getValue('show_categories_home'),
                'show_tags'                      => Tools::getValue('show_tags'),
                'show_tags_home'                 => Tools::getValue('show_tags_home'),
                'active_index'                   => Tools::getValue('active_index'),
                'show_article_product_page'      => Tools::getValue('show_article_product_page'),
                'hook'                           => Tools::getValue('hook'),
                'show_search'                    => Tools::getValue('show_search'),
                'show_search_home'               => Tools::getValue('show_search_home'),
                'using_captcha'                  => Tools::getValue('using_captcha'),
                'use_comments'                   => Tools::getValue('use_comments'),
                'validate_comments'              => Tools::getValue('validate_comments'),
                'unregistered_users'             => Tools::getValue('unregistered_users'),
                'new_comments'                   => Tools::getValue('new_comments'),
                'send_email'                     => Tools::getValue('send_email'),
                'related_products_description'   => Tools::getValue('related_products_description'),
                'number_related_products'        => Tools::getValue('number_related_products'),
                'featured_posts'                 => Tools::getValue('featured_posts'),
                'featured_posts_home'            => Tools::getValue('featured_posts_home'),
                'number_featured_posts'          => Tools::getValue('number_featured_posts'),
                'image_list_height'              => Tools::getValue('image_list_height'),
                'image_list_width'               => Tools::getValue('image_list_width'),
                'image_grid_height'              => Tools::getValue('image_grid_height'),
                'image_grid_width'               => Tools::getValue('image_grid_width'),
                'image_home_height'              => Tools::getValue('image_home_height'),
                'image_home_width'               => Tools::getValue('image_home_width'),
                'image_featured_height'          => Tools::getValue('image_featured_height'),
                'image_featured_width'           => Tools::getValue('image_featured_width'),
                'button_facebook'                => Tools::getValue('button_facebook'),
                'button_twitter'                 => Tools::getValue('button_twitter'),
                'button_googleplus'              => Tools::getValue('button_googleplus'),
                'button_linkedin'                => Tools::getValue('button_linkedin'),
                'button_email'                   => Tools::getValue('button_email'),
                'button_pinterest'               => Tools::getValue('button_pinterest'),
                'button_pocket'                  => Tools::getValue('button_pocket'),
                'button_tumblr'                  => Tools::getValue('button_tumblr'),
                'button_reddit'                  => Tools::getValue('button_reddit'),
                'button_hackernews'              => Tools::getValue('button_hackernews'),
            ];
            $valid = $this->_validFieldsConfig($config);
            $message = '';
            if ($valid !== false) {
                $config = serialize($config);
                if (Configuration::updateValue('GOMAKOIL_FUNCTIONAL_BLOG', $config)) {
                    $message = $this->displayConfirmation($this->l('Data successfully saved!'));
                }
            }
            if (Tools::getValue('regenerate_images') !== false) {
                $posts = $this->_objPost->getPost();
                $config = Tools::unSerialize($config);
                $images_types = [
                    [
                        'name'   => 'image_list',
                        'height' => $config['image_list_height'],
                        'width'  => $config['image_list_width'],
                    ],
                    [
                        'name'   => 'image_home',
                        'height' => $config['image_home_height'],
                        'width'  => $config['image_home_width'],
                    ],
                    [
                        'name'   => 'image_grid',
                        'height' => $config['image_grid_height'],
                        'width'  => $config['image_grid_width'],
                    ],
                    [
                        'name'   => 'image_featured',
                        'height' => $config['image_featured_height'],
                        'width'  => $config['image_featured_width'],
                    ],
                ];
                foreach ($posts as $tmpPost) {
                    if (file_exists(_PS_IMG_DIR_ . 'blog/' . date('Y-m',
                            strtotime($tmpPost['date_add'])) . '/' . $tmpPost['id_blog_post'] . '.jpg')) {
                        foreach ($images_types as $image_type) {
                            ImageManager::resize(
                                _PS_IMG_DIR_ . 'blog/' . date('Y-m',
                                    strtotime($tmpPost['date_add'])) . '/' . $tmpPost['id_blog_post'] . '.jpg',
                                _PS_IMG_DIR_ . 'blog/' . date('Y-m',
                                    strtotime($tmpPost['date_add'])) . '/' . $tmpPost['id_blog_post'] . '-' . Tools::stripslashes($image_type['name']) . '.jpg',
                                (int)$image_type['width'], (int)$image_type['height']
                            );
                        }
                    }
                }
                $message = $this->displayConfirmation($this->l('The thumbnails were successfully regenerated.'));
            }
            $this->_html .= $message;
        }
    }

    private function _validFieldsConfig($config)
    {
        if (!$config['count_post']) {
            $this->_html .= $this->displayError($this->l('Invalid value, ( Number of articles per page )!'));
            return false;
        }
        if ($config['new_comments'] && !$config['send_email']) {
            $this->_html .= $this->displayError($this->l('Invalid value, ( Email )'));
            return false;
        } elseif ($config['new_comments']) {
            if (!Validate::isEmail(trim($config['send_email']))) {
                $this->_html .= $this->displayError($this->l('Invalid value, ( Email )'));
                return false;
            }
        }
        if (!$config['number_related_products']) {
            $this->_html .= $this->displayError($this->l('Invalid value, ( Number of related products in slider )'));
            return false;
        }
        if (($config['featured_posts'] || $config['featured_posts']) && !$config['number_featured_posts']) {
            $this->_html .= $this->displayError($this->l('Invalid value, ( Number of featured articles in slider )'));
            return false;
        }
        if (!$config['number_articles_home_page_shop']) {
            $this->_html .= $this->displayError($this->l('Invalid value, ( Number of latest articles in block)'));
            return false;
        }
        if (!$config['number_articles_footer']) {
            $this->_html .= $this->displayError($this->l('Invalid value, ( Number of latest articles in block)'));
            return false;
        }
        return true;
    }

    public function displayForm()
    {
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        $fields_form = [];
        $img = Tools::unSerialize(Configuration::get('GOMAKOIL_FUNCTIONAL_BLOG_IMG'));
        $type = Tools::strtolower(Tools::substr(strrchr($img, '.'), 1));
        $image = dirname(__FILE__) . '/views/img/index.' . $img;
        $image_url = ImageManager::thumbnail($image, $this->table . $type, 350, $type, true, true);
        $image_size = file_exists($image) ? filesize($image) / 1000 : false;
        $hook = [
            [
                'id'   => 'displayHomeContent1',
                'val'  => 'displayHomeContent1',
                'name' => $this->l('displayHomeContent1')
            ],
            [
                'id'   => 'displayHomeContent2',
                'val'  => 'displayHomeContent2',
                'name' => $this->l('displayHomeContent2')
            ],
            [
                'id'   => 'displayHomeContent3',
                'val'  => 'displayHomeContent3',
                'name' => $this->l('displayHomeContent3')
            ],
            [
                'id'   => 'displayHomeContent4',
                'val'  => 'displayHomeContent4',
                'name' => $this->l('displayHomeContent4')
            ],
            [
                'id'   => 'displayHomeContent5',
                'val'  => 'displayHomeContent5',
                'name' => $this->l('displayHomeContent5')
            ],
        ];
        $fields_form[0]['form'] = [
            'legend'  => [
                'title' => $this->l('Blog settings'),
                'icon'  => 'icon-cogs'
            ],
            'tabs'    => [
                'general_settings'    => $this->l('General'),
                'index_settings'      => $this->l('Index page blog'),
                'comments_settings'   => $this->l('Comments'),
                'related_products'    => $this->l('Related products'),
                'related_articles'    => $this->l('Featured articles'),
                'latest_articles'     => $this->l('Latest articles'),
                'image_settings'      => $this->l('Cover image articles'),
                'soc_button_settings' => $this->l('Socials buttons share'),
                'modules'             => $this->l('Related Modules'),
            ],
            'input'   => [
                [
                    'type'             => 'html',
                    'tab'              => 'modules',
                    'form_group_class' => 'support_tab_content support_block',
                    'name'             => $this->displayTabModules()
                ],
                [
                    'type'  => 'text',
                    'label' => $this->l('Meta title'),
                    'name'  => 'index_meta_title',
                    'class' => 'index_meta_title',
                    'tab'   => 'index_settings',
                    'lang'  => true,
                ],
                [
                    'type'  => 'text',
                    'label' => $this->l('Meta description'),
                    'name'  => 'index_meta_description',
                    'class' => 'index_meta_description',
                    'tab'   => 'index_settings',
                    'lang'  => true,
                ],
                [
                    'type'  => 'text',
                    'label' => $this->l('Meta keywords'),
                    'name'  => 'index_meta_keywords',
                    'class' => 'index_meta_keywords',
                    'tab'   => 'index_settings',
                    'lang'  => true,
                ],
                [
                    'type'             => 'html',
                    'tab'              => 'index_settings',
                    'form_group_class' => 'settings_form_line',
                    'name'             => '<div></div>',
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('Active block description'),
                    'name'    => 'active_index',
                    'class'   => 'active_index',
                    'is_bool' => true,
                    'tab'     => 'index_settings',
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'          => 'file',
                    'label'         => $this->l('Image'),
                    'name'          => 'image_index',
                    'tab'           => 'index_settings',
                    'display_image' => true,
                    'image'         => $image_url ? $image_url : false,
                    'size'          => $image_size,
                    'delete_url'    => AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&deleteImageIndex=1',
                ],
                [
                    'type'         => 'textarea',
                    'label'        => $this->l('Description'),
                    'name'         => 'description_index',
                    'lang'         => true,
                    'autoload_rte' => true,
                    'tab'          => 'index_settings',
                    'rows'         => 10,
                    'cols'         => 100,
                    'hint'         => $this->l('Invalid characters:') . ' <>;=#{}'
                ],
                [
                    'type'     => 'text',
                    'label'    => $this->l('Articles per page'),
                    'name'     => 'count_post',
                    'class'    => 'count_post',
                    'tab'      => 'general_settings',
                    'required' => true,
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('Show search on blog block in left column'),
                    'name'    => 'show_search',
                    'class'   => 'show_search',
                    'is_bool' => true,
                    'tab'     => 'general_settings',
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'             => 'switch',
                    'label'            => $this->l('Show just on blog pages'),
                    'name'             => 'show_search_home',
                    'form_group_class' => 'show_search_home',
                    'is_bool'          => true,
                    'tab'              => 'general_settings',
                    'values'           => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'             => 'html',
                    'tab'              => 'general_settings',
                    'form_group_class' => 'settings_form_line',
                    'name'             => '<div></div>',
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('Show article categories block in left column'),
                    'name'    => 'show_categories',
                    'class'   => 'show_categories',
                    'tab'     => 'general_settings',
                    'is_bool' => true,
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'             => 'switch',
                    'label'            => $this->l('Show just on blog pages'),
                    'name'             => 'show_categories_home',
                    'form_group_class' => 'show_categories_home',
                    'is_bool'          => true,
                    'tab'              => 'general_settings',
                    'values'           => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'             => 'html',
                    'tab'              => 'general_settings',
                    'form_group_class' => 'settings_form_line',
                    'name'             => '<div></div>',
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('Show articles archive block in left column'),
                    'name'    => 'show_archive',
                    'class'   => 'show_archive',
                    'tab'     => 'general_settings',
                    'is_bool' => true,
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'             => 'switch',
                    'label'            => $this->l('Show just on blog pages'),
                    'name'             => 'show_archive_home',
                    'form_group_class' => 'show_archive_home',
                    'is_bool'          => true,
                    'tab'              => 'general_settings',
                    'values'           => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'             => 'html',
                    'tab'              => 'general_settings',
                    'form_group_class' => 'settings_form_line',
                    'name'             => '<div></div>',
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('Show articles tag block in left column'),
                    'name'    => 'show_tags',
                    'class'   => 'show_tags',
                    'tab'     => 'general_settings',
                    'is_bool' => true,
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'             => 'switch',
                    'label'            => $this->l('Show just on blog pages'),
                    'name'             => 'show_tags_home',
                    'form_group_class' => 'show_tags_home',
                    'tab'              => 'general_settings',
                    'is_bool'          => true,
                    'values'           => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'             => 'html',
                    'tab'              => 'general_settings',
                    'form_group_class' => 'settings_form_line',
                    'name'             => '<div></div>',
                ],
                [
                    'type'             => 'switch',
                    'label'            => $this->l('Show articles in related product page'),
                    'name'             => 'show_article_product_page',
                    'form_group_class' => 'show_article_product_page',
                    'tab'              => 'general_settings',
                    'is_bool'          => true,
                    'values'           => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'             => 'html',
                    'tab'              => 'general_settings',
                    'form_group_class' => 'settings_form_line',
                    'name'             => '<div></div>',
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('Active'),
                    'name'    => 'use_comments',
                    'class'   => 'use_comments',
                    'tab'     => 'comments_settings',
                    'is_bool' => true,
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('Captcha'),
                    'name'    => 'using_captcha',
                    'class'   => 'using_captcha',
                    'tab'     => 'comments_settings',
                    'is_bool' => true,
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('All comments must be validated by an employee'),
                    'name'    => 'validate_comments',
                    'class'   => 'validate_comments',
                    'tab'     => 'comments_settings',
                    'is_bool' => true,
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('Allow guest comments'),
                    'name'    => 'unregistered_users',
                    'class'   => 'unregistered_users',
                    'tab'     => 'comments_settings',
                    'is_bool' => true,
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('Notify about new comments'),
                    'name'    => 'new_comments',
                    'class'   => 'new_comments',
                    'tab'     => 'comments_settings',
                    'is_bool' => true,
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'             => 'text',
                    'label'            => $this->l('Send notification for'),
                    'name'             => 'send_email',
                    'tab'              => 'comments_settings',
                    'class'            => 'send_email',
                    'form_group_class' => 'send_email',
                    'hint'             => 'Each email must be separated by a comma'
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('Show product description'),
                    'name'    => 'related_products_description',
                    'class'   => 'related_products_description',
                    'tab'     => 'related_products',
                    'is_bool' => true,
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'  => 'text',
                    'label' => $this->l('Number of related products in slider'),
                    'name'  => 'number_related_products',
                    'class' => 'number_related_products',
                    'tab'   => 'related_products',
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('Show featured articles block in left column'),
                    'name'    => 'featured_posts',
                    'class'   => 'featured_posts',
                    'tab'     => 'related_articles',
                    'is_bool' => true,
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'             => 'switch',
                    'label'            => $this->l('Show just on blog pages'),
                    'name'             => 'featured_posts_home',
                    'tab'              => 'related_articles',
                    'form_group_class' => 'featured_home',
                    'is_bool'          => true,
                    'values'           => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('Show latest articles block in footer'),
                    'name'    => 'articles_footer',
                    'class'   => 'articles_footer',
                    'tab'     => 'latest_articles',
                    'is_bool' => true,
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'  => 'text',
                    'label' => $this->l('Number of latest articles in block'),
                    'name'  => 'number_articles_footer',
                    'class' => 'number_articles_footer',
                    'tab'   => 'latest_articles',
                ],
                [
                    'type'             => 'html',
                    'tab'              => 'latest_articles',
                    'form_group_class' => 'settings_form_line',
                    'name'             => '<div></div>',
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('Show latest articles block in home page shop'),
                    'name'    => 'articles_home_page_shop',
                    'class'   => 'articles_home_page_shop',
                    'tab'     => 'latest_articles',
                    'is_bool' => true,
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'  => 'text',
                    'label' => $this->l('Number of latest articles in block'),
                    'name'  => 'number_articles_home_page_shop',
                    'class' => 'number_articles_home_page_shop',
                    'tab'   => 'latest_articles',
                ],
                [
                    'type'    => 'select',
                    'label'   => $this->l('Hook'),
                    'name'    => 'hook',
                    'tab'     => 'latest_articles',
                    'options' => [
                        'query' => $hook,
                        'id'    => 'id',
                        'name'  => 'name'
                    ]
                ],
                [
                    'type'             => 'switch',
                    'label'            => $this->l('Show block social button'),
                    'name'             => 'show_social_button',
                    'form_group_class' => 'show_social_button_form',
                    'is_bool'          => true,
                    'tab'              => 'soc_button_settings',
                    'values'           => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'             => 'html',
                    'tab'              => 'soc_button_settings',
                    'form_group_class' => 'settings_form_line',
                    'name'             => '<div></div>',
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('Facebook'),
                    'name'    => 'button_facebook',
                    'is_bool' => true,
                    'tab'     => 'soc_button_settings',
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('Twitter'),
                    'name'    => 'button_twitter',
                    'is_bool' => true,
                    'tab'     => 'soc_button_settings',
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('Google +'),
                    'name'    => 'button_googleplus',
                    'is_bool' => true,
                    'tab'     => 'soc_button_settings',
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('Linkedin'),
                    'name'    => 'button_linkedin',
                    'is_bool' => true,
                    'tab'     => 'soc_button_settings',
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('Email'),
                    'name'    => 'button_email',
                    'is_bool' => true,
                    'tab'     => 'soc_button_settings',
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('Pinterest'),
                    'name'    => 'button_pinterest',
                    'is_bool' => true,
                    'tab'     => 'soc_button_settings',
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('Pocket'),
                    'name'    => 'button_pocket',
                    'is_bool' => true,
                    'tab'     => 'soc_button_settings',
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('Tumblr'),
                    'name'    => 'button_tumblr',
                    'is_bool' => true,
                    'tab'     => 'soc_button_settings',
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('Reddit'),
                    'name'    => 'button_reddit',
                    'is_bool' => true,
                    'tab'     => 'soc_button_settings',
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'    => 'switch',
                    'label'   => $this->l('Hackernews'),
                    'name'    => 'button_hackernews',
                    'is_bool' => true,
                    'tab'     => 'soc_button_settings',
                    'values'  => [
                        [
                            'id'    => 'display_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id'    => 'display_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ],
                    ],
                ],
                [
                    'type'  => 'text',
                    'label' => $this->l('Number of featured articles in slider'),
                    'name'  => 'number_featured_posts',
                    'class' => 'number_featured_posts',
                    'tab'   => 'related_articles',
                ],
                [
                    'type'             => 'html',
                    'tab'              => 'image_settings',
                    'form_group_class' => 'image_settings_form',
                    'name'             => '<div class="panel-heading"> <i class="icon-picture"></i> ' . $this->l('Image size for list view') . '</div>',
                ],
                [
                    'type'  => 'text',
                    'label' => $this->l('Width'),
                    'name'  => 'image_list_width',
                    'class' => 'image_list_width',
                    'tab'   => 'image_settings',
                ],
                [
                    'type'  => 'text',
                    'label' => $this->l('Height'),
                    'name'  => 'image_list_height',
                    'class' => 'image_list_height',
                    'tab'   => 'image_settings',
                ],
                [
                    'type'             => 'html',
                    'name'             => '<div class="panel-heading"> <i class="icon-picture"></i> ' . $this->l('Image size for grid view') . '</div>',
                    'tab'              => 'image_settings',
                    'form_group_class' => 'image_settings_form',
                ],
                [
                    'type'  => 'text',
                    'label' => $this->l('Width'),
                    'name'  => 'image_grid_width',
                    'class' => 'image_grid_width',
                    'tab'   => 'image_settings',
                ],
                [
                    'type'  => 'text',
                    'label' => $this->l('Height'),
                    'name'  => 'image_grid_height',
                    'class' => 'image_grid_height',
                    'tab'   => 'image_settings',
                ],
                [
                    'type'             => 'html',
                    'name'             => '<div class="panel-heading"> <i class="icon-picture"></i> ' . $this->l('Image size for index page site') . '</div>',
                    'tab'              => 'image_settings',
                    'form_group_class' => 'image_settings_form',
                ],
                [
                    'type'  => 'text',
                    'label' => $this->l('Width'),
                    'name'  => 'image_home_width',
                    'class' => 'image_home_width',
                    'tab'   => 'image_settings',
                ],
                [
                    'type'  => 'text',
                    'label' => $this->l('Height'),
                    'name'  => 'image_home_height',
                    'class' => 'image_home_height',
                    'tab'   => 'image_settings',
                ],
                [
                    'type'             => 'html',
                    'tab'              => 'image_settings',
                    'form_group_class' => 'image_settings_form',
                    'name'             => '<div class="panel-heading"> <i class="icon-picture"></i> ' . $this->l('Image size for featured articles block in left column') . '</div>',
                ],
                [
                    'type'  => 'text',
                    'label' => $this->l('Width'),
                    'name'  => 'image_featured_width',
                    'class' => 'image_featured_width',
                    'tab'   => 'image_settings',
                ],
                [
                    'type'  => 'text',
                    'label' => $this->l('Height'),
                    'name'  => 'image_featured_height',
                    'class' => 'image_featured_height',
                    'tab'   => 'image_settings',
                ],
            ],
            'buttons' => [
                [
                    'title' => $this->l('Regenerate thumbnails'),
                    'icon'  => 'process-icon-cogs',
                    'name'  => 'regenerate_images',
                    'type'  => 'submit'
                ]
            ],
            'submit'  => [
                'title' => $this->l('Save'),
            ]
        ];
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        foreach (Language::getLanguages(false) as $lang) {
            $helper->languages[] = [
                'id_lang'    => $lang['id_lang'],
                'iso_code'   => $lang['iso_code'],
                'name'       => $lang['name'],
                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
            ];
        }
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'saveBlogSettings';
        $helper->toolbar_btn = [
            'save' =>
                [
                    'desc' => $this->l('Save'),
                    'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                        '&token=' . Tools::getAdminTokenLite('AdminModules'),
                ],
            'back' => [
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            ]
        ];
        $config = Tools::unserialize(Configuration::get('GOMAKOIL_FUNCTIONAL_BLOG'));
        $descriptionAll = $this->getDescriptionIndex();
        $description = [];
        $index_meta_title = [];
        $index_meta_description = [];
        $index_meta_keywords = [];
        foreach ($descriptionAll as $descr) {
            $description[$descr['id_lang']] = $descr['description'];
            $index_meta_title[$descr['id_lang']] = $descr['meta_title'];
            $index_meta_description[$descr['id_lang']] = $descr['meta_description'];
            $index_meta_keywords[$descr['id_lang']] = $descr['meta_keywords'];
        }
        foreach (Language::getLanguages(false) as $lang) {
            if (!isset($description[$lang['id_lang']]) || !$description[$lang['id_lang']]) {
                $description[$lang['id_lang']] = '';
            }
            if (!isset($index_meta_title[$lang['id_lang']]) || !$index_meta_title[$lang['id_lang']]) {
                $index_meta_title[$lang['id_lang']] = '';
            }
            if (!isset($index_meta_description[$lang['id_lang']]) || !$index_meta_description[$lang['id_lang']]) {
                $index_meta_description[$lang['id_lang']] = '';
            }
            if (!isset($index_meta_keywords[$lang['id_lang']]) || !$index_meta_keywords[$lang['id_lang']]) {
                $index_meta_keywords[$lang['id_lang']] = '';
            }
        }
        if ($config) {
            foreach ($config as $key => $value) {
                $helper->fields_value[$key] = $value;
            }
        } else {
            foreach ($this->_emptySettings as $key => $value) {
                $helper->fields_value[$key] = $value;
            }
        }
        $helper->fields_value['description_index'] = $description;
        $helper->fields_value['index_meta_title'] = $index_meta_title;
        $helper->fields_value['index_meta_description'] = $index_meta_description;
        $helper->fields_value['index_meta_keywords'] = $index_meta_keywords;
        $this->_html .= $helper->generateForm($fields_form);
    }

    public function supportBlock()
    {
        return $this->display(__FILE__, 'views/templates/hook/support.tpl');
    }

    public function displayTabModules()
    {
        return $this->display(__FILE__, 'views/templates/hook/modules.tpl');
    }

    public function searchProducts($search, $id_shop, $id_lang, $id_blog_post)
    {
        include_once(_PS_MODULE_DIR_ . 'mpm_blog/datamodel.php');
        $this->_model = new blogDataModel();
        if (isset($id_blog_post) && $id_blog_post && $id_blog_post !== 'undefined') {
            $objPost = new blogPost();
            $related_products = $objPost->getRelatedProducts($id_lang, $id_shop, $id_blog_post);
        } else {
            $related_products = Configuration::get('GOMAKOIL_PRODUCTS_CHECKED');
        }
        $products_check = Tools::unserialize($related_products);
        $products = $this->_model->searchProduct($id_shop, $id_lang, $search);
        $this->context->smarty->assign(
            [
                'data'        => $products,
                'items_check' => $products_check,
                'name'        => 'products[]',
                'id'          => 'id_product',
                'title'       => 'name',
                'class'       => 'select_products'
            ]
        );
        return $this->display(__FILE__, 'views/templates/hook/filterForm.tpl');
    }

    public function showCheckedProducts($id_shop, $id_lang, $id_blog_post)
    {
        include_once(_PS_MODULE_DIR_ . 'mpm_blog/datamodel.php');
        $this->_model = new blogDataModel();
        if (isset($id_blog_post) && $id_blog_post && $id_blog_post !== 'undefined') {
            $objPost = new blogPost();
            $related_products = $objPost->getRelatedProducts($id_lang, $id_shop, $id_blog_post);
        } else {
            $related_products = Configuration::get('GOMAKOIL_PRODUCTS_CHECKED');
        }
        $products_check = Tools::unserialize($related_products);
        if (!$products_check) {
            $products_check = "";
        }
        $products = $this->_model->showCheckedProducts($id_shop, $id_lang, $products_check);
        $this->context->smarty->assign(
            [
                'data'        => $products,
                'items_check' => $products_check,
                'name'        => 'products[]',
                'id'          => 'id_product',
                'title'       => 'name',
                'class'       => 'select_products'
            ]
        );
        return $this->display(__FILE__, 'views/templates/hook/filterForm.tpl');
    }

    public function showAllProducts($id_shop, $id_lang, $id_blog_post)
    {
        include_once(_PS_MODULE_DIR_ . 'mpm_blog/datamodel.php');
        $this->_model = new blogDataModel();
        if (isset($id_blog_post) && $id_blog_post && $id_blog_post !== 'undefined') {
            $objPost = new blogPost();
            $related_products = $objPost->getRelatedProducts($id_lang, $id_shop, $id_blog_post);
        } else {
            $related_products = Configuration::get('GOMAKOIL_PRODUCTS_CHECKED');
        }
        $products_check = Tools::unserialize($related_products);
        if (!$products_check) {
            $products_check = "";
        }
        $products = $this->_model->showCheckedProducts($id_shop, $id_lang, false);
        $this->context->smarty->assign(
            [
                'data'        => $products,
                'items_check' => $products_check,
                'name'        => 'products[]',
                'id'          => 'id_product',
                'title'       => 'name',
                'class'       => 'select_products'
            ]
        );
        return $this->display(__FILE__, 'views/templates/hook/filterForm.tpl');
    }

    public function searchPosts($search, $id_shop, $id_lang, $id_blog_post)
    {
        include_once(_PS_MODULE_DIR_ . 'mpm_blog/datamodel.php');
        $this->_model = new blogDataModel();
        if (isset($id_blog_post) && $id_blog_post && $id_blog_post !== 'undefined') {
            $objPost = new blogPost();
            $related_posts = $objPost->getRelatedPosts($id_lang, $id_shop, $id_blog_post);
        } else {
            $related_posts = Configuration::get('GOMAKOIL_POSTS_CHECKED');
        }
        $items_check = Tools::unserialize($related_posts);
        $items = $this->_model->searchPost($search, $id_shop, $id_lang, $id_blog_post);
        $this->context->smarty->assign(
            [
                'data'        => $items,
                'items_check' => $items_check,
                'name'        => 'posts[]',
                'id'          => 'id_blog_post',
                'title'       => 'name',
                'class'       => 'select_posts'
            ]
        );
        return $this->display(__FILE__, 'views/templates/hook/filterForm.tpl');
    }

    public function showCheckedPosts($id_shop, $id_lang, $id_blog_post)
    {
        include_once(_PS_MODULE_DIR_ . 'mpm_blog/datamodel.php');
        $this->_model = new blogDataModel();
        if (isset($id_blog_post) && $id_blog_post && $id_blog_post !== 'undefined') {
            $objPost = new blogPost();
            $related_posts = $objPost->getRelatedPosts($id_lang, $id_shop, $id_blog_post);
        } else {
            $related_posts = Configuration::get('GOMAKOIL_POSTS_CHECKED');
        }
        $items_check = Tools::unserialize($related_posts);
        if (!$items_check) {
            $items_check = "";
        }
        $items = $this->_model->showCheckedPost($items_check, $id_shop, $id_lang);
        $this->context->smarty->assign(
            [
                'data'        => $items,
                'items_check' => $items_check,
                'name'        => 'posts[]',
                'id'          => 'id_blog_post',
                'title'       => 'name',
                'class'       => 'select_posts'
            ]
        );
        return $this->display(__FILE__, 'views/templates/hook/filterForm.tpl');
    }

    public function showAllPosts($id_shop, $id_lang, $id_blog_post)
    {
        include_once(_PS_MODULE_DIR_ . 'mpm_blog/datamodel.php');
        $this->_model = new blogDataModel();
        if (isset($id_blog_post) && $id_blog_post && $id_blog_post !== 'undefined') {
            $objPost = new blogPost();
            $related_posts = $objPost->getRelatedPosts($id_lang, $id_shop, $id_blog_post);
        } else {
            $related_posts = Configuration::get('GOMAKOIL_POSTS_CHECKED');
        }
        $items_check = Tools::unserialize($related_posts);
        if (!$items_check) {
            $items_check = "";
        }
        $items = $this->_model->showCheckedPost(false, $id_shop, $id_lang);
        $this->context->smarty->assign(
            [
                'data'        => $items,
                'items_check' => $items_check,
                'name'        => 'posts[]',
                'id'          => 'id_blog_post',
                'title'       => 'name',
                'class'       => 'select_posts'
            ]
        );
        return $this->display(__FILE__, 'views/templates/hook/filterForm.tpl');
    }

    public function captchaBlog()
    {
        $settings = Tools::unserialize(Configuration::get('GOMAKOIL_FUNCTIONAL_BLOG'));
        if ($settings['using_captcha']) {
            include_once(dirname(__FILE__) . "/simple-php-captcha.php");
            $captcha = simple_php_captcha([]);
        } else {
            $captcha = false;
        }
        $this->context->smarty->assign(
            [
                'captcha' => $captcha,
            ]
        );
        return $this->display(__FILE__, 'views/templates/hook/captcha.tpl');
    }

    public function hookDisplayProductExtraContent()
    {
        $array = [];
        $settings = Tools::unserialize(Configuration::get('GOMAKOIL_FUNCTIONAL_BLOG'));
        if (!$settings['show_article_product_page']) {
            return [];
        }
        $tpl = $this->getRelatedArticles();
        if (!$tpl) {
            return [];
        }
        $this->context->controller->registerStylesheet('blog_articles', 'modules/mpm_blog/views/css/style_prod.css',
            ['media' => 'all', 'priority' => 150]);
        $array[] = (new PrestaShop\PrestaShop\Core\Product\ProductExtraContent())
            ->setTitle($this->l('Related articles on blog'))
            ->setContent($tpl);
        return $array;
    }

    public function getRelatedArticles()
    {
        $articles = [];
        $id_product = Tools::getValue('id_product');
        $posts = $this->getRelatedPost($this->_langId, $this->_shopId, $id_product);
        foreach ($posts as $post) {
            $products = Tools::unSerialize($post['id_related_products']);
            if ($products && in_array($id_product, $products)) {
                $articles[] = $post;
            }
        }
        if (!$articles) {
            return false;
        }
        $link = new Link();
        $baseUrl = $link->getPageLink('display-faq-home', true);
        $this->context->smarty->assign([
            'blogUrl'  => $baseUrl,
            'articles' => $articles,
        ]);
        return $this->display(__FILE__, 'views/templates/front/tab.tpl');
    }

    public function getRelatedPost($id_lang = false, $id_shop = false)
    {
        $sql = 'SELECT bp.id_blog_post, bp.id_related_products, bpl.link_rewrite, bcl.link_rewrite as link_rewrite_category, bpl.name
        FROM ' . _DB_PREFIX_ . 'blog_category bc
        LEFT JOIN ' . _DB_PREFIX_ . 'blog_post bp
        ON (bp.id_blog_category = bc.id_blog_category)
        LEFT JOIN ' . _DB_PREFIX_ . 'blog_post_lang bpl
        ON (bp.id_blog_post = bpl.id_blog_post AND bpl.id_shop = ' . ($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')) . ')
        LEFT JOIN ' . _DB_PREFIX_ . 'blog_category_lang bcl
        ON (bc.id_blog_category = bcl.id_blog_category AND bcl.id_shop = ' . ($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')) . ')
        WHERE bp.active=1
        AND bpl.id_lang=' . ($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')) . '
        GROUP BY bp.id_blog_post
       ';
        return Db::getInstance()->ExecuteS($sql);
    }

    public function getIdSettings($id_lang)
    {
        $sql = 'SELECT bc.id_blog_index_page_lang as id
        FROM ' . _DB_PREFIX_ . 'blog_index_page_lang bc
        WHERE  bc.id_lang=' . (int)$id_lang . '
       ';
        $res = Db::getInstance()->ExecuteS($sql);
        if (isset($res[0]['id']) && $res[0]['id']) {
            return $res[0]['id'];
        } else {
            return false;
        }
    }

    public function getDescriptionIndex()
    {
        $sql = 'SELECT *
        FROM ' . _DB_PREFIX_ . 'blog_index_page_lang bc ';
        return Db::getInstance()->ExecuteS($sql);
    }

    public function hookFooter($params)
    {
        $settings = Tools::unserialize(Configuration::get('GOMAKOIL_FUNCTIONAL_BLOG'));
        if (!$settings['articles_footer']) {
            return false;
        }
        $link = new Link();
        $baseUrl = $link->getPageLink('display-faq-home', true);
        $latest = $this->getLatestPost($this->_langId, $this->_shopId, $settings['number_articles_footer']);
        if (!$latest) {
            return false;
        }
        $this->context->smarty->assign([
            'articles' => $latest,
            'blogUrl'  => $baseUrl,
        ]);
        return $this->display(__FILE__, 'views/templates/front/footer.tpl');
    }

    public function getLatestPost($id_lang = false, $id_shop = false, $limit = false)
    {
        if ($limit) {
            $where = ' LIMIT ' . (int)$limit;
        }
        $sql = 'SELECT bp.id_blog_post, bp.id_related_products, bpl.link_rewrite,  bpl.description_short, bcl.link_rewrite as link_rewrite_category, bpl.name, bp.date_add
        FROM ' . _DB_PREFIX_ . 'blog_category bc
        LEFT JOIN ' . _DB_PREFIX_ . 'blog_post bp
        ON (bp.id_blog_category = bc.id_blog_category)
        LEFT JOIN ' . _DB_PREFIX_ . 'blog_post_lang bpl
        ON (bp.id_blog_post = bpl.id_blog_post AND bpl.id_shop = ' . ($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')) . ')
        LEFT JOIN ' . _DB_PREFIX_ . 'blog_category_lang bcl
        ON (bc.id_blog_category = bcl.id_blog_category AND bcl.id_shop = ' . ($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')) . ')
        WHERE bp.active=1
        AND bpl.id_lang=' . ($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')) . '
        GROUP BY bp.id_blog_post
        ORDER BY bp.date_add DESC
        ' . $where . '
       ';
        return Db::getInstance()->ExecuteS($sql);
    }

    public function hookDisplayHomeContent1()
    {
        $settings = Tools::unserialize(Configuration::get('GOMAKOIL_FUNCTIONAL_BLOG'));
        $hook = $settings['hook'];
        if (($hook !== 'displayHomeContent1')) {
            return false;
        }
        if (!$settings['articles_home_page_shop']) {
            return false;
        }
        $link = new Link();
        $baseUrl = $link->getPageLink('display-faq-home', true);
        $articles = $this->getLatestPost($this->_langId, $this->_shopId,
            ((int)$settings['number_articles_home_page_shop'] + 10));
        if (!$articles) {
            return false;
        }
        $articles_res = [];
        foreach ($articles as $key => $tmpPost) {
            $articles[$key]['is_image'] = false;
            if (file_exists(_PS_IMG_DIR_ . 'blog/' . date('Y-m',
                    strtotime($tmpPost['date_add'])) . '/' . $tmpPost['id_blog_post'] . '.jpg')) {
                $articles_res[$key] = $tmpPost;
                $articles_res[$key]['is_image'] = _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'img/blog/' . date('Y-m',
                        strtotime($tmpPost['date_add'])) . '/';
            }
            if (count($articles_res) == (int)$settings['number_articles_home_page_shop']) {
                break;
            }
        }
        $this->context->smarty->assign([
            'articles' => $articles_res,
            'blogUrl'  => $baseUrl,
        ]);
        return $this->display(__FILE__, 'views/templates/front/home.tpl');
    }

    public function hookDisplayHomeContent2()
    {
        $settings = Tools::unserialize(Configuration::get('GOMAKOIL_FUNCTIONAL_BLOG'));
        $hook = $settings['hook'];
        if (($hook !== 'displayHomeContent2')) {
            return false;
        }
        if (!$settings['articles_home_page_shop']) {
            return false;
        }
        $link = new Link();
        $baseUrl = $link->getPageLink('display-faq-home', true);
        $articles = $this->getLatestPost($this->_langId, $this->_shopId,
            ((int)$settings['number_articles_home_page_shop'] + 10));
        if (!$articles) {
            return false;
        }
        $articles_res = [];
        foreach ($articles as $key => $tmpPost) {
            $articles[$key]['is_image'] = false;
            if (file_exists(_PS_IMG_DIR_ . 'blog/' . date('Y-m',
                    strtotime($tmpPost['date_add'])) . '/' . $tmpPost['id_blog_post'] . '.jpg')) {
                $articles_res[$key] = $tmpPost;
                $articles_res[$key]['is_image'] = _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'img/blog/' . date('Y-m',
                        strtotime($tmpPost['date_add'])) . '/';
            }
            if (count($articles_res) == (int)$settings['number_articles_home_page_shop']) {
                break;
            }
        }
        $this->context->smarty->assign([
            'articles' => $articles_res,
            'blogUrl'  => $baseUrl,
        ]);
        return $this->display(__FILE__, 'views/templates/front/home.tpl');
    }

    public function hookDisplayHomeContent3()
    {
        $settings = Tools::unserialize(Configuration::get('GOMAKOIL_FUNCTIONAL_BLOG'));
        $hook = $settings['hook'];
        if (($hook !== 'displayHomeContent1')) {
            return false;
        }
        if (!$settings['articles_home_page_shop']) {
            return false;
        }
        $link = new Link();
        $baseUrl = $link->getPageLink('display-faq-home', true);
        $articles = $this->getLatestPost($this->_langId, $this->_shopId,
            ((int)$settings['number_articles_home_page_shop'] + 10));
        if (!$articles) {
            return false;
        }
        $articles_res = [];
        foreach ($articles as $key => $tmpPost) {
            $articles[$key]['is_image'] = false;
            if (file_exists(_PS_IMG_DIR_ . 'blog/' . date('Y-m',
                    strtotime($tmpPost['date_add'])) . '/' . $tmpPost['id_blog_post'] . '.jpg')) {
                $articles_res[$key] = $tmpPost;
                $articles_res[$key]['is_image'] = _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'img/blog/' . date('Y-m',
                        strtotime($tmpPost['date_add'])) . '/';
            }
            if (count($articles_res) == (int)$settings['number_articles_home_page_shop']) {
                break;
            }
        }
        $this->context->smarty->assign([
            'articles' => $articles_res,
            'blogUrl'  => $baseUrl,
        ]);
        return $this->display(__FILE__, 'views/templates/front/home.tpl');
    }

    public function hookDisplayHomeContent4()
    {
        $settings = Tools::unserialize(Configuration::get('GOMAKOIL_FUNCTIONAL_BLOG'));
        $hook = $settings['hook'];
        if (($hook !== 'displayHomeContent4')) {
            return false;
        }
        if (!$settings['articles_home_page_shop']) {
            return false;
        }
        $link = new Link();
        $baseUrl = $link->getPageLink('display-faq-home', true);
        $articles = $this->getLatestPost($this->_langId, $this->_shopId,
            ((int)$settings['number_articles_home_page_shop'] + 10));
        if (!$articles) {
            return false;
        }
        $articles_res = [];
        foreach ($articles as $key => $tmpPost) {
            $articles[$key]['is_image'] = false;
            if (file_exists(_PS_IMG_DIR_ . 'blog/' . date('Y-m',
                    strtotime($tmpPost['date_add'])) . '/' . $tmpPost['id_blog_post'] . '.jpg')) {
                $articles_res[$key] = $tmpPost;
                $articles_res[$key]['is_image'] = _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'img/blog/' . date('Y-m',
                        strtotime($tmpPost['date_add'])) . '/';
            }
            if (count($articles_res) == (int)$settings['number_articles_home_page_shop']) {
                break;
            }
        }
        $this->context->smarty->assign([
            'articles' => $articles_res,
            'blogUrl'  => $baseUrl,
        ]);
        return $this->display(__FILE__, 'views/templates/front/home.tpl');
    }

    public function hookDisplayHomeContent5()
    {
        $settings = Tools::unserialize(Configuration::get('GOMAKOIL_FUNCTIONAL_BLOG'));
        $hook = $settings['hook'];
        if (($hook !== 'displayHomeContent5')) {
            return false;
        }
        if (!$settings['articles_home_page_shop']) {
            return false;
        }
        $link = new Link();
        $baseUrl = $link->getPageLink('display-faq-home', true);
        $articles = $this->getLatestPost($this->_langId, $this->_shopId,
            ((int)$settings['number_articles_home_page_shop'] + 10));
        if (!$articles) {
            return false;
        }
        $articles_res = [];
        foreach ($articles as $key => $tmpPost) {
            $articles[$key]['is_image'] = false;
            if (file_exists(_PS_IMG_DIR_ . 'blog/' . date('Y-m',
                    strtotime($tmpPost['date_add'])) . '/' . $tmpPost['id_blog_post'] . '.jpg')) {
                $articles_res[$key] = $tmpPost;
                $articles_res[$key]['is_image'] = _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'img/blog/' . date('Y-m',
                        strtotime($tmpPost['date_add'])) . '/';
            }
            if (count($articles_res) == (int)$settings['number_articles_home_page_shop']) {
                break;
            }
        }
        $this->context->smarty->assign([
            'articles' => $articles_res,
            'blogUrl'  => $baseUrl,
        ]);
        return $this->display(__FILE__, 'views/templates/front/home.tpl');
    }

    public function renderWidget($hookName = null, array $configuration = [])
    {
        if (!$this->active) {
            return false;
        }
        if ($hookName == null && isset($configuration['hook'])) {
            $hookName = $configuration['hook'];
        }
        $this->getWidgetVariables($hookName, $configuration);
        if ($hookName == 'displayLeftColumn') {
            return $this->hookdisplayLeftColumn();
        }
    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
    }

    public function messageTpl($post, $comment, $raty, $name, $email, $url)
    {
        $this->context->smarty->assign([
            'post'    => $post,
            'comment' => $comment,
            'raty'    => $raty,
            'name'    => $name,
            'email'   => $email,
            'url'     => $url,
        ]);
        return $this->display(__FILE__, 'views/templates/front/message.tpl');
    }
}