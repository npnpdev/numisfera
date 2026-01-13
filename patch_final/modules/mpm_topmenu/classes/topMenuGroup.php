<?php

class topMenuGroup extends ObjectModel
{
  public $id_topmenu_group;
  public $id_topmenu_column;
  public $id_topmenu;
  public $ident;
  public $active;
  public $position;
  public $title;
  public $title_front;
  public $text_color;
  public $text_color_hover;
  public $background_color;
  public $description;
  public $description_before;
  public $description_after;
  public $date_add;
  public $type;
  public $categories;
  public $products;
  public $cms;
  public $link;
  public $brands;
  public $suppliers;
  public $pages;
  public $video;
  public $images;
  public $subcategories;
  public $product_title;
  public $product_img;
  public $product_price;
  public $product_add;
  public $type_img;


  public static $definition = array(
    'table' => 'topmenu_group',
    'primary' => 'id_topmenu_group',
    'multilang' => true,
    'multilang_shop' => true,
    'fields' => array(

      // Base fields
      'id_topmenu_column' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'id_topmenu' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'ident' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'active'   => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
      'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
      'position' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'subcategories' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'product_title' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'product_img' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'product_price' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'product_add' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'type_img' => 	array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
      'title' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
      'background_color' =>			array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'),
      'text_color' =>			array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'),
      'text_color_hover' =>			array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'),
      'type' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
      'categories' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
      'products' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
      'cms' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
      'link' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
      'brands' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
      'suppliers' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
      'pages' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
      'images' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
      'video' =>			array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'),

      // Lang fields
      'title_front' =>			array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
      'description' =>			array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
      'description_before' =>			array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
      'description_after' =>			array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
    )
  );

  public function __construct($id_topmenu_group = null, $id_lang = null, $id_shop = null)
  {
    parent::__construct($id_topmenu_group, $id_lang, $id_shop);
    Shop::addTableAssociation('topmenu_group', array('type' => 'fk_shop'));
  }

  public function update($null_values = false)
  {
    $res = parent::update($null_values);
    return $res;
  }

  public function delete()
  {
    $res = parent::delete();
    return $res;
  }

  public function add($autodate = true, $null_values = false)
  {
    $res = parent::add($autodate, $null_values);
    return $res;
  }



}