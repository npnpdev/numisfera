<?php

class homeBlocks extends ObjectModel
{
  public $id_homeblocks;
  public $active;
  public $position;
  public $width;

  public $description;

  public $date_add;
  public $background_color;

  public $min_height;


  public static $definition = array(
    'table' => 'homeblocks',
    'primary' => 'id_homeblocks',
    'multilang' => true,
    'multilang_shop' => true,
    'fields' => array(

      //basic fields
      'background_color' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml',  'size' => 255),

      'active' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'position' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'min_height' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
      'date_add' =>            array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
      'width' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),

      // Lang fields
      'description' =>			array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml',  'lang' => true),


    )
  );

  public function __construct($id_homeblocks = null, $id_lang = null, $id_shop = null)
  {
    parent::__construct($id_homeblocks, $id_lang, $id_shop);
    Shop::addTableAssociation('homeblocks', array('type' => 'fk_shop'));
  }

  public function update($null_values = false)
  {
    $res = parent::update($null_values);
    return $res;
  }

  public function delete()
  {
    unlink(_PS_MODULE_DIR_ . 'mpm_homeblocks/views/img/'.$this->id.'.png');
    $res = parent::delete();
    return $res;
  }

  public function add($autodate = true, $null_values = false)
  {
    if(!$this->getLastPosition() && $this->getLastPosition() !== 0){
      $position = 0;
    }
    else{
      $position = (int)$this->getLastPosition() + 1;
    }
    $this->position = $position;
    $res = parent::add($autodate, $null_values);
    return $res;
  }
  public function getLastPosition()
  {
    return (int)(Db::getInstance()->getValue('
		SELECT MAX(s.`position`)
		FROM `'._DB_PREFIX_.'homeblocks` s
		') );
  }


}