<?php

class blockHomeSlide extends ObjectModel
{
  public $id_block_home_slider;
  public $active;
  public $position;
  public $position_desc;
  public $width_desc = 0;
  public $height_desc = 0;
  public $opacity_desc = 0;
  public $date_add;
  public $title;
  public $url;
  public $caption;
  public $description;
  public $image;
  public $id_image;

  public static $definition = array(
    'table' => 'block_home_slider',
    'primary' => 'id_block_home_slider',
    'multilang' => true,
    'multilang_shop' => true,
    'fields' => array(
      //basic fields

      'active' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'position' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'position_desc' =>     array('type' => self::TYPE_STRING,  'validate' => 'isString'),
      'width_desc' => 	array('type' => self::TYPE_INT,  'validate' => 'isunsignedInt'),
      'height_desc' => 	array('type' => self::TYPE_INT,  'validate' => 'isunsignedInt'),
      'opacity_desc' => 	array('type' => self::TYPE_FLOAT,  'validate' => 'isFloat'),
      'date_add' =>            array('type' => self::TYPE_DATE, 'validate' => 'isDate'),

      // Lang fields
      'image' => 	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml',  'size' => 255),
      'title' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml',  'size' => 255),
      'url' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml',  'size' => 255),
      'caption' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml',  'size' => 255),
      'description' =>			array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
    )
  );

  public function __construct($id_block_home_slider = null, $id_lang = null, $id_shop = null)
  {
    $this->image_dir = _PS_MODULE_DIR_ . 'mpm_homeslider/views/img/';
    $this->id_image = ($this->id && file_exists($this->image_dir.(int)$this->id.'.jpg')) ? (int)$this->id : false;

    parent::__construct($id_block_home_slider, $id_lang, $id_shop);

    Shop::addTableAssociation('block_home_slider_lang', array('type' => 'fk_shop'));
  }

  public function update($null_values = false)
  {
    $res = parent::update($null_values);
    return $res;
  }

  public function delete()
  {
    $this->deleteImgSlider($this->image);
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
		FROM `'._DB_PREFIX_.'block_home_slider` s
		') );
  }

  public function deleteImgSlider($images){

    $dir = _PS_MODULE_DIR_ . 'mpm_homeslider/views/img/';
      foreach($images as $key => $val){
        if(file_exists($dir.$val)){
          @unlink($dir.$val);
        }
        if(file_exists($dir.'slides/'.$val)){
          @unlink($dir.'slides/'.$val);
        }
      }
  }

  public function getAllImagesSlides(){
    $result = array();
    $sql = '
        SELECT sl.image
        FROM ' . _DB_PREFIX_ . 'block_home_slider as s
        INNER JOIN ' . _DB_PREFIX_ . 'block_home_slider_lang as sl
        ON s.id_block_home_slider = sl.id_block_home_slider
        ';
    $images = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    if(isset($images[0]['image']) && $images[0]['image']){
      foreach($images as $value){
        $result[] = $value['image'];
      }
    }
    return $result;
  }

  public function getSlidesImg(){
    $sql = '
			SELECT GROUP_CONCAT(DISTINCT sl.image) as value
      FROM ' . _DB_PREFIX_ . 'block_home_slider as s
      INNER JOIN ' . _DB_PREFIX_ . 'block_home_slider_lang as sl
      ON s.id_block_home_slider = sl.id_block_home_slider
			';

    $images = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    if(isset($images[0]['value']) && $images[0]['value']){
      return explode(',', $images[0]['value']);
    }
    else{
      return false;
    }
  }

  public function resizeSliderImages($id = false){
    $dir = _PS_MODULE_DIR_ . 'mpm_homeslider/views/img/';
    $dir_slides = _PS_MODULE_DIR_ . 'mpm_homeslider/views/img/slides/';
    $settings = Tools::unserialize(Configuration::get('GOMAKOIL_HOME_SLIDER'));
    $width = $settings['width'];
    $height = $settings['height'];

   if($id !== false){
     $languages = Language::getLanguages(false);
     foreach ($languages as $language)
     {
       $size = file_exists($dir.$id.'_'.$language['id_lang'].'.jpg') ? filesize($dir.$id.'_'.$language['id_lang'].'.jpg') / 1000 : false;
       if($size){
         $resize = ImageManager::resize($dir.$id.'_'.$language['id_lang'].'.jpg', $dir_slides.$id.'_'.$language['id_lang'].'.jpg', (int)$width, (int)$height, 'jpg');
       }
     }
   }
   else{
      $ids = $this->getSlidesImg();

     if($ids){
       foreach($ids as $val){
         $size = file_exists($dir.$val) ? filesize($dir.$val) / 1000 : false;

         if($size){
           $resize = ImageManager::resize($dir.$val, $dir_slides.$val, (int)$width, (int)$height, 'jpg');
         }
         $resize = ImageManager::resize($dir.'default.jpg', $dir_slides.'default.jpg', (int)$width, (int)$height, 'jpg');
       }
     }
    }
    return true;
  }

  public function getAllSlides($id_lang, $id_shop){
    $sql = '
			SELECT *
      FROM ' . _DB_PREFIX_ . 'block_home_slider as s
      LEFT JOIN ' . _DB_PREFIX_ . 'block_home_slider_lang as sl
      ON s.id_block_home_slider = sl.id_block_home_slider
      WHERE sl.id_shop = ' . (int)$id_shop . '
      AND sl.id_lang = '. (int)$id_lang .'
      AND s.active = 1
      ORDER BY s.position

			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

}