<?php
class blogComments extends ObjectModel
{
  public $id_blog_comment;
  public $id_blog_post;
  public $id_shop;
  public $date_add;
  public $active = 1;
  public $rating;
  public $author_name;
  public $author_email;
  public $content;

  public $html;

  public static $definition = array(
    'table' => 'blog_comment',
    'primary' => 'id_blog_comment',
    'multilang' => false,
    'multilang_shop' => false,
    'fields' => array(
      //basic fields
      'id_shop' => 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
      'id_blog_post' => 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
      'date_add' => 			array('type' => self::TYPE_DATE, 'validate' => 'isString'),
      'rating' => 			array('type' => self::TYPE_DATE, 'validate' => 'isString'),
      'content' => 			array('type' => self::TYPE_STRING, 'validate' => 'isString'),
      'author_name' => 			array('type' => self::TYPE_DATE, 'validate' => 'isString'),
      'author_email' => 			array('type' => self::TYPE_DATE, 'validate' => 'isString'),
      'active'  => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
    )
  );
  public	function __construct($blog_comment = null, $id_lang = null, $id_shop = null)
  {
    parent::__construct($blog_comment, $id_lang, $id_shop);
  }


  public function getComments($id_shop = false, $id_lang = false, $article = false, $limit_c = false)
  {
    $limit = '';
    if($limit_c){
//      $limit = ' LIMIT '.$limit_c;
    }

    $sql = 'SELECT * FROM (SELECT bc.content, bc.author_name, DATE_FORMAT(bc.date_add, "%Y/%m/%d") as date, bc.date_add as date_sort
        FROM '._DB_PREFIX_.'blog_comment bc
        LEFT JOIN '._DB_PREFIX_.'blog_post bp
        ON (bc.id_blog_post = bp.id_blog_post)
        LEFT JOIN '._DB_PREFIX_.'blog_post_lang bpl
        ON (bp.id_blog_post = bpl.id_blog_post AND bpl.id_shop = '.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')).')
        WHERE bc.active=1
        AND bpl.link_rewrite= "'. pSQL($article) .'"
        AND bpl.id_lang='.($id_lang ? (int)$id_lang : Configuration::get('PS_LANG_DEFAULT')) .'
        AND bc.id_shop='.($id_shop ? (int)$id_shop : Configuration::get('PS_SHOP_DEFAULT')) .'
        GROUP BY bc.id_blog_comment
        ORDER BY bc.id_blog_comment DESC
        '.$limit.') g ORDER BY g.date_sort'
    ;

    return Db::getInstance()->ExecuteS($sql);
  }

//  public function toggleStatus()
//  {
//    var_dump(11);die;
//  }

}