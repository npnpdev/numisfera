<?php

class customBlock extends ObjectModel
{
  public $id_customblock;
  public $active;
  public $position;
  public $date_add;
  public $title;
  public $description;


  public static $definition = array(
    'table' => 'customblock',
    'primary' => 'id_customblock',
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

  public function __construct($id_customblock = null, $id_lang = null, $id_shop = null)
  {
    parent::__construct($id_customblock, $id_lang, $id_shop);
    Shop::addTableAssociation('customblock', array('type' => 'fk_shop'));
  }

  public function update($null_values = false)
  {
    $res = parent::update($null_values);
    return $res;
  }

  public function delete()
  {
    unlink(_PS_MODULE_DIR_ . 'mpm_customblock/views/img/'.$this->id.'.png');
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
		FROM `'._DB_PREFIX_.'customblock` s
		') );
  }


  public function getCustomBlock($id_lang, $id_shop){
    $sql = '
      SELECT  *
        FROM ' . _DB_PREFIX_ . 'customblock c
        LEFT JOIN ' . _DB_PREFIX_ . 'customblock_lang as cl
        ON c.id_customblock = cl.id_customblock
        WHERE cl.id_lang = ' . $id_lang . '
        AND cl.id_shop = '.$id_shop.'
        AND c.active = 1
        ORDER BY c.position
			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

}