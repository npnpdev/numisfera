<?php
class blogPost extends ObjectModel
{
  public $id_blog_post;
  public $id_blog_category;
  public $date_add;
  public $allow_comment = 1;
  public $active = 1;
  public $show_in_most = 1;
  public $position;
  public $id_related_posts;
  public $id_related_products;
  public $name;
  public $tags;
  public $description_short;
  public $description;
  public $meta_title;
  public $meta_description;
  public $meta_keywords;
  public $link_rewrite;

  public static $definition = array(
    'table' => 'blog_post',
    'primary' => 'id_blog_post',
    'multilang' => true,
    'multilang_shop' => false,
    'fields' => array(
      //basic fields

      'date_add' => 			array('type' => self::TYPE_DATE, 'validate' => 'isString'),
      'allow_comment'  => array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'id_related_posts'  => array('type' => self::TYPE_STRING,'validate' => 'isCleanHtml'),
      'id_related_products'  => array('type' => self::TYPE_STRING,'validate' => 'isCleanHtml'),
      'id_blog_category' =>		array('type' => self::TYPE_INT, 'validate' => 'isInt','required' => true),
      'active'  => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'show_in_most'  => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'position' =>		array('type' => self::TYPE_INT,'validate' => 'isunsignedInt'),
      'active'  => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),

      // Lang fields
      'name' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 255),
      'description_short' => 	array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'),
      'description' => 	array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'),
      'tags' => 	array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'),
      'meta_title' => 			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 255),
      'meta_description' => 				array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 255),
      'meta_keywords' => 				array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 128),
      'link_rewrite' => 				array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isLinkRewrite', 'required' => true, 'size' => 128),
    )
  );
  public function __construct($id_blog_post = null, $id_lang = null, $id_shop = null)
  {
    parent::__construct($id_blog_post, $id_lang, $id_shop);
    ShopCore::addTableAssociation('blog_post_lang', array('type' => 'fk_shop'));

  }

  public function update($null_values = false)
  {
    $cat = Tools::getValue('categoryBox');


    if($this->id_blog_category !== $cat && $cat){
      $this->position = (int)$this->getLastPostPosition($cat) + 1;
    }
    if($cat){
      $this->id_blog_category = $cat;
    }
    $rez = parent::update($null_values);
    return $rez;
  }

  public function add($autodate = true, $null_values = false)
  {
    $related_products = Configuration::get('GOMAKOIL_PRODUCTS_CHECKED');
    $related_posts = Configuration::get('GOMAKOIL_POSTS_CHECKED');
    if(isset($related_products)){
      $this->id_related_products = $related_products;
    }
    if(isset($related_posts)){
      $this->id_related_posts = $related_posts;
    }
    $cat = Tools::getValue('categoryBox');
    if(isset($cat))
    {
      $this->id_blog_category = $cat;
    }
    $this->position = (int)$this->getLastPostPosition($cat) + 1;
    $res = parent::add($autodate, $null_values);

    Configuration::updateValue('GOMAKOIL_PRODUCTS_CHECKED', '');
    Configuration::updateValue('GOMAKOIL_POSTS_CHECKED', '');
    return $res;
  }

  public function getLastPostPosition( $category = false)
  {
    return (int)(Db::getInstance()->getValue('
      SELECT MAX(bp.`position`)
      FROM `'._DB_PREFIX_.'blog_post` bp
      WHERE  bp.`id_blog_category` = '.(int)$category
      )
    );
  }

  public function getPostIsset($id_shop = false, $id_post = false)
  {
    $sql = 'SELECT bps.*
        FROM '._DB_PREFIX_.'blog_post_shop bps
        WHERE bps.id_blog_post= '.(int)$id_post.'
        AND bps.id_shop='.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT'));
    $rez = Db::getInstance()->ExecuteS($sql);
    if($rez[0]['id_blog_post']){
      $rez = $rez[0]['id_blog_post'];
    }
    else{
      $rez = false;
    }
    return $rez;
  }

  public function getPost($id_lang = false, $id_shop = false, $id_category = false, $excludedPost = false)
  {
    $where = '';
    if($id_category){
      $where .= ' AND bc.id_blog_category = '. $id_category;
    }
    if( $excludedPost ){
      $where .= ' AND bp.id_blog_post != '. $excludedPost;
    }
    $sql = 'SELECT bpl.*, bc.*, bp.*
        FROM '._DB_PREFIX_.'blog_category bc
        LEFT JOIN '._DB_PREFIX_.'blog_post bp
        ON (bp.id_blog_category = bc.id_blog_category)
        LEFT JOIN '._DB_PREFIX_.'blog_post_lang bpl
        ON (bp.id_blog_post = bpl.id_blog_post AND bpl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).')
        LEFT JOIN '._DB_PREFIX_.'blog_category_lang bcl
        ON (bc.id_blog_category = bcl.id_blog_category AND bcl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).')
        WHERE bp.active=1
        AND bc.active=1
        AND bpl.id_lang='.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')).'
        AND bcl.id_lang='.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT'))
        .$where ;
    return Db::getInstance()->ExecuteS($sql);
  }

  public function getRelatedProducts($id_lang = false, $id_shop = false, $id_blog_post= false){
    $sql = 'SELECT bp.id_related_products
        FROM '._DB_PREFIX_.'blog_post bp
        LEFT JOIN '._DB_PREFIX_.'blog_post_lang bpl
        ON (bp.id_blog_post = bpl.id_blog_post AND bpl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).')
        WHERE bp.id_blog_post=' . (int)$id_blog_post . '
        AND bpl.id_lang='.($id_lang ? (int)$id_lang : (int)Configuration::get('PS_LANG_DEFAULT'));
    $rez = Db::getInstance()->ExecuteS($sql);
    return $rez[0]['id_related_products'];

  }

  public function getRelatedPosts($id_lang = false, $id_shop = false, $id_blog_post = false){
    $sql = 'SELECT bp.id_related_posts
        FROM '._DB_PREFIX_.'blog_post bp
        LEFT JOIN '._DB_PREFIX_.'blog_post_lang bpl
        ON (bp.id_blog_post = bpl.id_blog_post AND bpl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).')
        WHERE bp.id_blog_post=' . (int)$id_blog_post . '
        AND bpl.id_lang='.($id_lang ? (int)$id_lang : (int)Configuration::get('PS_LANG_DEFAULT'));
    $rez = Db::getInstance()->ExecuteS($sql);
    return $rez[0]['id_related_posts'];

  }

  public function getTags($id_lang = false, $id_shop = false){
    $sql = 'SELECT bpl.tags
        FROM '._DB_PREFIX_.'blog_post_lang bpl
        WHERE bpl.tags IS NOT NULL AND bpl.tags != ""
        AND bpl.id_lang='.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')).'
        AND bpl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).'
        LIMIT 15'    ;
    return Db::getInstance()->ExecuteS($sql);
  }

  public function getPostByMonth($id_lang = false, $id_shop = false){
    $sql = 'SELECT  DATE_FORMAT(bp.date_add, "%Y-%m") as date,
        count(DATE_FORMAT(bp.date_add, "%Y %M")) as count,
        DATE_FORMAT(bp.date_add, "%Y-%m") as rez
        FROM '._DB_PREFIX_.'blog_post bp
        LEFT JOIN '._DB_PREFIX_.'blog_post_lang bpl
        ON (bp.id_blog_post = bpl.id_blog_post AND bpl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).')
        LEFT JOIN '._DB_PREFIX_.'blog_category bc
        ON (bp.id_blog_category = bc.id_blog_category )
        WHERE bp.active=1
        AND bc.active=1
        AND bpl.id_lang='.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')) .'
        GROUP BY rez
        ORDER BY rez DESC
        LIMIT 8'
    ;
    return Db::getInstance()->ExecuteS($sql);
  }

  public function getPostFeatured($id_lang = false, $id_shop = false){
    $sql = 'SELECT bp.id_blog_post, bpl.link_rewrite, bcl.link_rewrite as link_rewrite_category,
        bpl.name, bpl.description_short,
        DATE_FORMAT(bp.date_add, "%Y/%m/%d") as date_add
        FROM '._DB_PREFIX_.'blog_post bp
        LEFT JOIN '._DB_PREFIX_.'blog_post_lang bpl
        ON (bp.id_blog_post = bpl.id_blog_post AND bpl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).')
        LEFT JOIN '._DB_PREFIX_.'blog_category bc
        ON (bp.id_blog_category = bc.id_blog_category )
        LEFT JOIN '._DB_PREFIX_.'blog_category_lang bcl
        ON (bcl.id_blog_category = bc.id_blog_category AND bcl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).' AND bcl.id_lang = '.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')).')
        WHERE bp.show_in_most=1
        AND bc.active=1
        AND bp.active=1
        AND bpl.id_lang='.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')) .'
        GROUP BY bp.id_blog_post DESC';

    return Db::getInstance()->ExecuteS($sql);
  }

  public function getPostsByLinkRewrite($category=false, $orderBy = true, $id_lang = false, $id_shop = false, $limit = false){
    $where = '';
    $sort = '';
    $limit_p = '';
    if($category){
      $where = 'AND  bcl.link_rewrite = "'.pSQL($category).'" ';
    }
    if($orderBy){
      $sort = 'ORDER BY bp.id_blog_post DESC';
    }
    if($limit){
      $limit_p = ' LIMIT '.$limit;
    }

    $sql = 'SELECT bp.*,bpl.*, bcl.link_rewrite as link_rewrite_category,
    DATE_FORMAT(bp.date_add, "%Y") as date_y,DATE_FORMAT(bp.date_add, "%b") as date_m,
    DATE_FORMAT(bp.date_add, "%d") as date_d, bc.allow_comment as allow_comment_category,
    SUM(com.rating)/SUM(IF(com.rating,1,0)) as rating,
    count(com.id_blog_comment) as rating_count
        FROM '._DB_PREFIX_.'blog_post bp
        LEFT JOIN '._DB_PREFIX_.'blog_post_lang bpl
        ON (bp.id_blog_post = bpl.id_blog_post AND bpl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).')
        LEFT JOIN '._DB_PREFIX_.'blog_category bc
        ON (bp.id_blog_category = bc.id_blog_category )
        LEFT JOIN '._DB_PREFIX_.'blog_category_lang bcl
        ON (bcl.id_blog_category = bc.id_blog_category AND bcl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).' AND bcl.id_lang = '.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')).')
        LEFT JOIN  '._DB_PREFIX_.'blog_comment com
        ON (bp.id_blog_post = com.id_blog_post AND com.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).')
        WHERE bc.active=1
        '.$where.'
        AND bp.active=1
        AND bpl.id_lang='.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')) .'
        GROUP BY bp.id_blog_post
        '.$sort.$limit_p
    ;
    return Db::getInstance()->ExecuteS($sql);
  }

  public function getCountPostsByLinkRewrite($category=false, $id_lang = false, $id_shop = false){
    $where = '';
    if($category){
      $where = 'AND  bcl.link_rewrite = "'.pSQL($category).'" ';
    }
    $sql = 'SELECT count(bp.id_blog_post) as count
        FROM '._DB_PREFIX_.'blog_post bp
        LEFT JOIN '._DB_PREFIX_.'blog_post_lang bpl
        ON (bp.id_blog_post = bpl.id_blog_post AND bpl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).')
        LEFT JOIN '._DB_PREFIX_.'blog_category bc
        ON bp.id_blog_category = bc.id_blog_category
        LEFT JOIN '._DB_PREFIX_.'blog_category_lang bcl
        ON (bcl.id_blog_category = bc.id_blog_category AND bcl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).' AND bcl.id_lang = '.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')).')
        WHERE bc.active=1
        '.$where.'
        AND bp.active=1
        AND bpl.id_lang='.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT'))
    ;
    $rez = Db::getInstance()->ExecuteS($sql);
    if($rez){
      $rez = $rez[0]['count'];
    }
    else{
      $rez = false;
    }
    return $rez;
  }

  public function getPostByLinkRewrite($category=false, $article = false, $id_lang = false, $id_shop = false){
    $sql = 'SELECT bp.*, bpl.*, bcl.link_rewrite as link_rewrite_category, bcl.name as name_category, bc.allow_comment as allow_comment_category,
    DATE_FORMAT(bp.date_add, "%Y") as date_y,DATE_FORMAT(bp.date_add, "%b") as date_m,
    DATE_FORMAT(bp.date_add, "%d") as date_d,
    SUM(com.rating)/SUM(IF(com.rating,1,0)) as rating,
    count(com.id_blog_comment) as rating_count
        FROM '._DB_PREFIX_.'blog_post bp
        LEFT JOIN '._DB_PREFIX_.'blog_post_lang bpl
        ON (bp.id_blog_post = bpl.id_blog_post AND bpl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).')
        LEFT JOIN '._DB_PREFIX_.'blog_category bc
        ON (bp.id_blog_category = bc.id_blog_category )
        LEFT JOIN  '._DB_PREFIX_.'blog_comment com
        ON (bp.id_blog_post = com.id_blog_post AND com.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).')
        LEFT JOIN '._DB_PREFIX_.'blog_category_lang bcl
        ON (bcl.id_blog_category = bc.id_blog_category AND bcl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).' AND bcl.id_lang = '.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')).')
        WHERE bpl.link_rewrite = "'.pSQL($article).'"
        AND bc.active=1
        AND bcl.link_rewrite = "'.pSQL($category).'"
        AND bp.active=1
        AND bpl.id_lang='.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')) .'
        GROUP BY bp.id_blog_post
        ';

    return Db::getInstance()->ExecuteS($sql);
  }

  public function getArchive($id_lang = false, $id_shop = false, $date = false, $limit = false){
    $limit_p = '';
    if($limit){
      $limit_p = ' LIMIT '.$limit;
    }
    $sql = 'SELECT bp.*, bpl.*, bcl.link_rewrite as link_rewrite_category,
    DATE_FORMAT(bp.date_add, "%Y %M") as date, DATE_FORMAT(bp.date_add, "%Y") as date_y,
    DATE_FORMAT(bp.date_add, "%b") as date_m,DATE_FORMAT(bp.date_add, "%d") as date_d,
    DATE_FORMAT(bp.date_add, "%Y-%m") as rez,
    SUM(com.rating)/SUM(IF(com.rating,1,0)) as rating,
    count(com.id_blog_comment) as rating_count
        FROM '._DB_PREFIX_.'blog_post bp
        LEFT JOIN '._DB_PREFIX_.'blog_post_lang bpl
        ON (bp.id_blog_post = bpl.id_blog_post AND bpl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).')
        LEFT JOIN '._DB_PREFIX_.'blog_category bc
        ON (bp.id_blog_category = bc.id_blog_category )
        LEFT JOIN '._DB_PREFIX_.'blog_category_lang bcl
        ON (bcl.id_blog_category = bc.id_blog_category AND bcl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).' AND bcl.id_lang = '.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')).')
        LEFT JOIN  '._DB_PREFIX_.'blog_comment com
        ON (bp.id_blog_post = com.id_blog_post AND com.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).')
        WHERE bp.active=1
        AND bc.active=1
        AND bpl.id_lang='.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')) .'
        AND DATE_FORMAT(bp.date_add, "%Y-%m") = "'.pSQL($date).'"
        GROUP BY bp.id_blog_post' . ' ORDER BY bp.id_blog_post DESC ' .$limit_p

    ;
    return Db::getInstance()->ExecuteS($sql);
  }


  public function getCountArchive($id_lang = false, $id_shop = false, $date = false){

    $sql = 'SELECT count(bp.id_blog_post) as count
        FROM '._DB_PREFIX_.'blog_post bp
        LEFT JOIN '._DB_PREFIX_.'blog_post_lang bpl
        ON (bp.id_blog_post = bpl.id_blog_post AND bpl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).')
        LEFT JOIN '._DB_PREFIX_.'blog_category bc
        ON (bp.id_blog_category = bc.id_blog_category )
        LEFT JOIN '._DB_PREFIX_.'blog_category_lang bcl
        ON (bcl.id_blog_category = bc.id_blog_category AND bcl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).' AND bcl.id_lang = '.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')).')
        WHERE bp.active=1
        AND bc.active=1
        AND bpl.id_lang='.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')) .'
        AND DATE_FORMAT(bp.date_add, "%Y-%m") = "'.pSQL($date).'"'
    ;
    $rez = Db::getInstance()->ExecuteS($sql);
    if($rez){
      $rez = $rez[0]['count'];
    }
    else{
      $rez = false;
    }
    return $rez;
  }


  public function searchPost($id_lang = false, $id_shop = false, $search = false, $limit = false){

    $limit_p = '';
    if($limit){
      $limit_p = ' LIMIT '.$limit;
    }

    $where = "";
    if( $search ){
      $where = " AND (bpl.name LIKE '%".pSQL($search)."%' OR bpl.description_short LIKE '%".pSQL($search)."%' OR bpl.tags LIKE '%".pSQL($search)."%')";
    }

    $sql = 'SELECT bp.*, bpl.*, bcl.link_rewrite as link_rewrite_category,
    DATE_FORMAT(bp.date_add, "%Y") as date_y,DATE_FORMAT(bp.date_add, "%b") as date_m,
    DATE_FORMAT(bp.date_add, "%d") as date_d,
       SUM(com.rating)/SUM(IF(com.rating,1,0)) as rating,
       count(com.rating != 0) as rating_count
        FROM '._DB_PREFIX_.'blog_post bp
        LEFT JOIN '._DB_PREFIX_.'blog_post_lang bpl
        ON (bp.id_blog_post = bpl.id_blog_post AND bpl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).')
        LEFT JOIN '._DB_PREFIX_.'blog_category bc
        ON (bp.id_blog_category = bc.id_blog_category )
        LEFT JOIN '._DB_PREFIX_.'blog_category_lang bcl
        ON (bcl.id_blog_category = bc.id_blog_category AND bcl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).' AND bcl.id_lang = '.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')).')
        LEFT JOIN  '._DB_PREFIX_.'blog_comment com
        ON (bp.id_blog_post = com.id_blog_post AND com.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).')
        WHERE bc.active=1
        AND bp.active=1
        AND bpl.id_lang='.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')) .'
        '.$where.'
        GROUP BY bp.id_blog_post
        ORDER BY bp.id_blog_post DESC
        '.$limit_p
    ;
    return Db::getInstance()->ExecuteS($sql);
  }


  public function countSearchPost($id_lang = false, $id_shop = false, $search = false){
    $where = "";
    if( $search ){
      $where = " AND (bpl.name LIKE '%".pSQL($search)."%' OR bpl.description_short LIKE '%".pSQL($search)."%' OR bpl.tags LIKE '%".pSQL($search)."%')";
    }

    $sql = 'SELECT count(bp.id_blog_post) as count
        FROM '._DB_PREFIX_.'blog_post bp
        LEFT JOIN '._DB_PREFIX_.'blog_post_lang bpl
        ON (bp.id_blog_post = bpl.id_blog_post AND bpl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).')
        LEFT JOIN '._DB_PREFIX_.'blog_category bc
        ON (bp.id_blog_category = bc.id_blog_category )
        LEFT JOIN '._DB_PREFIX_.'blog_category_lang bcl
        ON (bcl.id_blog_category = bc.id_blog_category AND bcl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).' AND bcl.id_lang = '.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')).')
        WHERE bc.active=1
        AND bp.active=1
        AND bpl.id_lang='.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')).'
        '.$where.'
        ';
    $rez = Db::getInstance()->ExecuteS($sql);
    if($rez){
      $rez = $rez[0]['count'];
    }
    else{
      $rez = false;
    }
    return $rez;
  }

  public function getRelatedArticles($category = false, $article = false, $id_lang = false, $id_shop = false, $limit = false){
    if($limit){
      $limit = ' LIMIT '.$limit;
    }
    else{
      $limit = ' LIMIT 3';
    }
    $sql = 'SELECT bp.id_related_posts
        FROM '._DB_PREFIX_.'blog_post bp
        LEFT JOIN '._DB_PREFIX_.'blog_post_lang bpl
        ON (bp.id_blog_post = bpl.id_blog_post AND bpl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).')
        LEFT JOIN '._DB_PREFIX_.'blog_category bc
        ON (bp.id_blog_category = bc.id_blog_category )
        LEFT JOIN '._DB_PREFIX_.'blog_category_lang bcl
        ON (bcl.id_blog_category = bc.id_blog_category AND bcl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).' AND bcl.id_lang = '.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')).')
        WHERE bpl.link_rewrite = "'.pSQL($article).'"
        AND bc.active=1
        AND bcl.link_rewrite = "'.pSQL($category).'"
        AND bp.active=1
        AND bpl.id_lang='.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')).'
        GROUP BY bp.id_blog_post
        ';
    $rez = Db::getInstance()->ExecuteS($sql);
    if($rez){
      $rez = Tools::unserialize($rez[0]['id_related_posts']);
    }
    else{
      return false;
    }
    if(!$rez){
      return false;
    }

    $rez = implode(",", $rez);
    $sql = 'SELECT bpl.link_rewrite, bpl.name, bcl.link_rewrite as link_rewrite_category, bcl.name as name_category
        FROM '._DB_PREFIX_.'blog_post bp
        LEFT JOIN '._DB_PREFIX_.'blog_post_lang bpl
        ON (bp.id_blog_post = bpl.id_blog_post AND bpl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).')
        LEFT JOIN '._DB_PREFIX_.'blog_category bc
        ON (bp.id_blog_category = bc.id_blog_category )
        LEFT JOIN '._DB_PREFIX_.'blog_category_lang bcl
        ON (bcl.id_blog_category = bc.id_blog_category AND bcl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).' AND bcl.id_lang = '.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')).')
        WHERE bp.id_blog_post IN('.pSQL($rez).')
        AND bc.active=1
        AND bp.active=1
        AND bpl.id_lang='.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')).'
        GROUP BY bp.id_blog_post
        ORDER BY rand()
        '.$limit.'
        ';
    return Db::getInstance()->ExecuteS($sql);
  }

  public function getRelatedProductsById($category = false, $article = false, $id_lang = false, $id_shop = false){
    $sql = 'SELECT bp.id_related_products
        FROM '._DB_PREFIX_.'blog_post bp
        LEFT JOIN '._DB_PREFIX_.'blog_post_lang bpl
        ON (bp.id_blog_post = bpl.id_blog_post AND bpl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).')
        LEFT JOIN '._DB_PREFIX_.'blog_category bc
        ON (bp.id_blog_category = bc.id_blog_category)
        LEFT JOIN '._DB_PREFIX_.'blog_category_lang bcl
        ON (bcl.id_blog_category = bc.id_blog_category AND bcl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).' AND bcl.id_lang = '.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')).')
        WHERE bpl.link_rewrite = "'.pSQL($article).'"
        AND bc.active=1
        AND bcl.link_rewrite = "'.pSQL($category).'"
        AND bp.active=1
        AND bpl.id_lang='.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')).'
        GROUP BY bp.id_blog_post
        ';
    $rez = Db::getInstance()->ExecuteS($sql);
    if($rez){
      $rez = Tools::unserialize($rez[0]['id_related_products']);
    }
    else{
      return false;
    }
    if(!$rez){
      return false;
    }
    $rez = implode(",", $rez);
    $sql = '
			SELECT p.*, pl.*, i.*
      FROM ' . _DB_PREFIX_ . 'product_lang as pl
      INNER JOIN ' . _DB_PREFIX_ . 'product as p
      ON p.id_product = pl.id_product
      LEFT JOIN ' . _DB_PREFIX_ . 'image as i
      ON p.id_product = i.id_product
      WHERE pl.id_lang = ' . ($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')). '
      AND p.id_product IN('.pSQL($rez).')
      AND pl.id_shop = ' . ($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')) . '
      GROUP BY p.id_product
      ORDER BY rand()
			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function truncate($string, $limit, $pad="...")
  {
    if(mb_strlen($string) <= $limit){
      return $string;
    }

    mb_internal_encoding("UTF-8");
    $string = mb_substr($string, 0, $limit) . $pad;

    return $string;
  }
}
