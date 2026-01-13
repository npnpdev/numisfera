<?php

class homeBanners extends ObjectModel
{
  public $id_banners;
  public $hook;
  public $active;
  public $position;
  public $width_block_left;
  public $width_description_left;
  public $width_block_right;
  public $width_description_right;
  public $position_description_left;
  public $position_description_right;
  public $description_left;
  public $description_right;
  public $date_add;
  public $background_color_left;
  public $background_color_right;
  public $min_height;


  public static $definition = array(
    'table' => 'banners',
    'primary' => 'id_banners',
    'multilang' => true,
    'multilang_shop' => true,
    'fields' => array(

      //basic fields
      'hook' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml',  'size' => 255),
      'background_color_left' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml',  'size' => 255),
      'background_color_right' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml',  'size' => 255),
      'active' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'position' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'min_height' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'date_add' =>            array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
      'width_block_left' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'width_description_left' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'width_block_right' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'width_description_right' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'position_description_left' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml',  'size' => 255),
      'position_description_right' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml',  'size' => 255),

      // Lang fields
      'description_left' =>			array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml',  'lang' => true),
      'description_right' =>			array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml',  'lang' => true),

    )
  );

  public function __construct($id_banners = null, $id_lang = null, $id_shop = null)
  {
    parent::__construct($id_banners, $id_lang, $id_shop);
    Shop::addTableAssociation('banners', array('type' => 'fk_shop'));
  }

  public function update($null_values = false)
  {
    $res = parent::update($null_values);
    return $res;
  }

  public function delete()
  {
    unlink(_PS_MODULE_DIR_ . 'mpm_banners/views/img/'.$this->id.'_left.png');
    unlink(_PS_MODULE_DIR_ . 'mpm_banners/views/img/'.$this->id.'_right.png');
    $res = parent::delete();
    return $res;
  }

  public function add($autodate = true, $null_values = false)
  {
    if(!$this->getLastSlidesPosition() && $this->getLastSlidesPosition() !== 0){
      $position = 0;
    }
    else{
      $position = (int)$this->getLastSlidesPosition() + 1;
    }
    $this->position = $position;
    $res = parent::add($autodate, $null_values);
    return $res;
  }
  public function getLastSlidesPosition()
  {
    return (int)(Db::getInstance()->getValue('
		SELECT MAX(s.`position`)
		FROM `'._DB_PREFIX_.'banners` s
		') );
  }


  public function getSetiingsItem($id_lang, $id_shop, $hook = false){

    $where = "";
    if(isset($hook) && $hook){
      $where = ' AND b.hook = "' . pSQL($hook) .'"';
    }

    $sql = '
			SELECT *
      FROM ' . _DB_PREFIX_ . 'banners as b
      INNER JOIN ' . _DB_PREFIX_ . 'banners_lang as bl
      ON b.id_banners = bl.id_banners
      WHERE bl.id_lang = ' . (int)$id_lang . '
      AND bl.id_shop = ' . (int)$id_shop .'
      ' .$where .'
      AND b.active = 1
			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

}