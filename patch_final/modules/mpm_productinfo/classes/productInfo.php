<?php

class productInfo extends ObjectModel
{
  public $id_product_info;
  public $active;
  public $position;

  public $date_add;
  public $title;
  public $description;


  public static $definition = array(
    'table' => 'product_info',
    'primary' => 'id_product_info',
    'multilang' => true,
    'multilang_shop' => true,
    'fields' => array(
      //basic fields

      'active' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'position' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'date_add' =>            array('type' => self::TYPE_DATE, 'validate' => 'isDate'),

      // Lang fields
      'title' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml',  'size' => 255),
      'description' =>			array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
    )
  );

  public function __construct($id_product_info = null, $id_lang = null, $id_shop = null)
  {

    parent::__construct($id_product_info, $id_lang, $id_shop);

    Shop::addTableAssociation('product_info', array('type' => 'fk_shop'));
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
		FROM `'._DB_PREFIX_.'product_info` s
		') );
  }


}