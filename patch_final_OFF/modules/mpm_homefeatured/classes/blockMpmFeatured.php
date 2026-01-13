<?php

class blockMpmFeatured extends ObjectModel
{
  public $id_mpm_homefeatured;
  public $active = 1;
  public $hook;
  public $type;
  public $ids_products;
  public $ids_categories;
  public $date_add;
  public $title;

  public static $definition = array(
    'table' => 'mpm_homefeatured',
    'primary' => 'id_mpm_homefeatured',
    'multilang' => true,
    'multilang_shop' => true,
    'fields' => array(
      //basic fields

      'active' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'hook' =>			array('type' => self::TYPE_STRING,  'validate' => 'isCleanHtml'),
      'type' =>			array('type' => self::TYPE_STRING,  'validate' => 'isCleanHtml'),
      'ids_products' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
      'ids_categories' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
      'date_add' =>            array('type' => self::TYPE_DATE, 'validate' => 'isDate'),

      // Lang fields

      'title' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'required' => true, 'validate' => 'isCleanHtml',  'size' => 255),
    )
  );

  public function __construct($id_mpm_homefeatured = null, $id_lang = null, $id_shop = null)
  {

    parent::__construct($id_mpm_homefeatured, $id_lang, $id_shop);
    Shop::addTableAssociation('mpm_homefeatured_lang', array('type' => 'fk_shop'));
  }

  public function update($null_values = false)
  {
    $statusmpm_homefeatured = Tools::getValue('statusmpm_homefeatured');
    if(!$statusmpm_homefeatured && ($statusmpm_homefeatured !== "")){
      $idsProducts = Tools::getValue('idsProducts');
      $idsCategories = Tools::getValue('categoryBox');
      if($idsCategories){
        $idsCategories = implode(",", $idsCategories);
        $this->ids_categories = $idsCategories;
      }
      else{
        $this->ids_categories = '';
      }

      $this->ids_products = $idsProducts;
    }
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
    $idsProducts = Tools::getValue('idsProducts');
    $idsCategories = Tools::getValue('categoryBox');
    if($idsCategories){
      $idsCategories = implode(",", $idsCategories);
      $this->ids_categories = $idsCategories;
    }
    else{
      $this->ids_categories = '';
    }
    $this->ids_products = $idsProducts;

    $res = parent::add($autodate, $null_values);
    return $res;
  }

  public function getSetiingsItem($id_lang, $id_shop, $hook){

    $sql = '
			SELECT *
      FROM ' . _DB_PREFIX_ . 'mpm_homefeatured as h
      INNER JOIN ' . _DB_PREFIX_ . 'mpm_homefeatured_lang as hl
      ON h.id_mpm_homefeatured = hl.id_mpm_homefeatured
      WHERE hl.id_lang = ' . (int)$id_lang . '
      AND hl.id_shop = ' . (int)$id_shop .'
      AND h.hook = "' . pSQL($hook) .'"
      AND h.active = 1
			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }


}