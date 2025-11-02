<?php

class topMenu extends ObjectModel
{
  public $id_topmenu;
  public $active = 1;
  public $position;
  public $title;
  public $link;
  public $open_new_window;
  public $text_color_tab = '#000000';
  public $text_color_hover_tab = '#000000';
  public $background_color_tab = '#ffffff';
  public $background_color_hover_tab = '#ffffff';
  public $width = 0;
  public $min_height = 0;
  public $border_size = 0;

  public $border_color = '#ffffff';
  public $background_color = '#ffffff';
  public $description_before;
  public $description_after;
  public $date_add;


  public static $definition = array(
    'table' => 'topmenu',
    'primary' => 'id_topmenu',
    'multilang' => true,
    'multilang_shop' => true,
    'fields' => array(

      // Base fields
      'active'   => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
      'open_new_window'   => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
      'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
      'width' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'min_height' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'border_size' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'border_color' =>			array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'),
      'background_color' =>			array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'),
      'position' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'text_color_tab' =>			array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'),
      'text_color_hover_tab' =>			array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'),
      'background_color_tab' =>			array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'),
      'background_color_hover_tab' =>			array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'),

      // Lang fields
      'title' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'required' => true, 'validate' => 'isCleanHtml'),
      'link' =>			array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
      'description_before' =>			array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
      'description_after' =>			array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
    )
  );

  public function __construct($id_topmenu = null, $id_lang = null, $id_shop = null)
  {
    parent::__construct($id_topmenu, $id_lang, $id_shop);
    Shop::addTableAssociation('topmenu', array('type' => 'fk_shop'));
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
		FROM `'._DB_PREFIX_.'topmenu` s
		') );
  }


}