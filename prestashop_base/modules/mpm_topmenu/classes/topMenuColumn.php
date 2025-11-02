<?php

class topMenuColumn extends ObjectModel
{
  public $id_topmenu_column;
  public $id_topmenu;
  public $ident;
  public $active;
  public $position;
  public $title;
  public $text_color;
  public $text_color_hover;
  public $background_color;
  public $width;
  public $description_before;
  public $description_after;
  public $date_add;


  public static $definition = array(
    'table' => 'topmenu_column',
    'primary' => 'id_topmenu_column',
    'multilang' => true,
    'multilang_shop' => true,
    'fields' => array(

      // Base fields
      'active'   => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
      'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
      'id_topmenu' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'ident' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'position' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'width' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'title' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
      'background_color' =>			array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'),
      'text_color' =>			array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'),
      'text_color_hover' =>			array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'),

      // Lang fields
      'description_before' =>			array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
      'description_after' =>			array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
    )
  );

  public function __construct($id_topmenu_column = null, $id_lang = null, $id_shop = null)
  {
    parent::__construct($id_topmenu_column, $id_lang, $id_shop);
    Shop::addTableAssociation('topmenu_column', array('type' => 'fk_shop'));
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