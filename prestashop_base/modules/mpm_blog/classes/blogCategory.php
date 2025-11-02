<?php
class blogCategory extends ObjectModel
{
  public $id_blog_category;
  public $date_add;
  public $active = 1;
  public $position;
  public $allow_comment = 1;
  public $name;
  public $description;
  public $meta_title;
  public $meta_description;
  public $meta_keywords;
  public $link_rewrite;
  public $html;

  public static $definition = array(
    'table' => 'blog_category',
    'primary' => 'id_blog_category',
    'multilang' => true,
    'multilang_shop' => true,
    'fields' => array(
      //basic fields
      'date_add' => 			array('type' => self::TYPE_DATE, 'validate' => 'isString'),
      'active'  => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'position' =>		array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'allow_comment'  => array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      // Lang fields
      'name' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 255),
      'description' => 	array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'),
      'meta_title' => 			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString', 'size' => 255),
      'meta_description' => 				array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString', 'size' => 255),
      'meta_keywords' => 				array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString', 'size' => 128),
      'link_rewrite' => 				array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isLinkRewrite', 'required' => true, 'size' => 128),
    )
  );
  public	function __construct($blog_category = null, $id_lang = null, $id_shop = null)
  {
    parent::__construct($blog_category, $id_lang, $id_shop);

    Shop::addTableAssociation('blog_category_lang', array('type' => 'fk_shop'));
  }

  public function add($autodate = true, $null_values = false)
  {
    $this->position = (int)$this->getLastCategoryPosition() + 1;
    $ret = parent::add($autodate, $null_values);
    return $ret;
  }

  public function getCategories($id_lang = false, $id_shop = false)
  {
    $sql = 'SELECT bcl.*, bc.*, count(bp.id_blog_category) as count
        FROM '._DB_PREFIX_.'blog_category bc
        LEFT JOIN '._DB_PREFIX_.'blog_category_lang bcl
        ON (bc.id_blog_category = bcl.id_blog_category AND bcl.id_shop = '.($id_shop ? (int)$id_shop: Configuration::get('PS_SHOP_DEFAULT')).')
        LEFT JOIN '._DB_PREFIX_.'blog_post bp
        ON bc.id_blog_category = bp.id_blog_category
        WHERE bc.active=1
        AND bcl.id_lang='.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')).'
        GROUP BY bc.id_blog_category
        ORDER BY bc.position'
    ;

    return Db::getInstance()->ExecuteS($sql);
  }

  public function getLastCategoryPosition()
  {
    return (int)(Db::getInstance()->getValue('
		SELECT MAX(c.`position`)
		FROM `'._DB_PREFIX_.'blog_category` c
		') );
  }

  public function getCategoryByLink($category, $id_lang, $id_shop)
  {
    $sql = 'SELECT bcl.*, bc.date_add
        FROM '._DB_PREFIX_.'blog_category bc
        LEFT JOIN '._DB_PREFIX_.'blog_category_lang bcl
        ON (bc.id_blog_category = bcl.id_blog_category AND bcl.id_shop = '.($id_shop ? (int)$id_shop: Configuration::get('PS_SHOP_DEFAULT')).')
        WHERE bcl.link_rewrite="'.pSQL($category).'"
        AND bcl.id_lang='.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT'))
    ;

    $rez = Db::getInstance()->ExecuteS($sql);
    if($rez){
      $rez = $rez[0];
    }
    else{
      $rez = false;
    }

    return $rez;
  }



}