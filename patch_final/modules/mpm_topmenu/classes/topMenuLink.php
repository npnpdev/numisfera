<?php

class topMenuLink extends ObjectModel
{
  public $id_topmenu_link;
  public $active;
  public $title;
  public $link;
  public $date_add;


  public static $definition = array(
    'table' => 'topmenu_link',
    'primary' => 'id_topmenu_link',
    'multilang' => true,
    'multilang_shop' => true,
    'fields' => array(

      // Base fields
      'active'   => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
      'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),

      // Lang fields
      'title' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'required' => true, 'validate' => 'isCleanHtml'),
      'link' =>			array('type' => self::TYPE_HTML, 'lang' => true, 'required' => true,  'validate' => 'isCleanHtml'),

    )
  );

  public function __construct($id_topmenu_link = null, $id_lang = null, $id_shop = null)
  {
    parent::__construct($id_topmenu_link, $id_lang, $id_shop);
    Shop::addTableAssociation('topmenu_link', array('type' => 'fk_shop'));
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