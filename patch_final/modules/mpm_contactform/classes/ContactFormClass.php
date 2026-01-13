<?php

class ContactFormClass extends ObjectModel
{
  public $id_contactform;
  public $email;
  public $background;
  public $color;
  public $block_description;
  public $block_form;
  public $block_image;
  public $block_maps;
  public $position_description;
  public $position_form;
  public $position_image;
  public $position_maps;
  public $name_field;
  public $email_field;
  public $phone_field;
  public $subject_field;
  public $captcha_field;
  public $attach_field;
  public $name_field_required;
  public $email_field_required;
  public $phone_field_required;
  public $subject_field_required;
  public $background_button;
  public $background_button_hover;
  public $color_button;
  public $maps_code;
  public $width_maps;
  public $height_maps;
  public $width_description;
  public $width_form;
  public $width_image;
  public $width_maps_block;
  public $date_add;
  public $title_block_description;
  public $description;
  public $title_block_form;
  public $title_block_image;
  public $title_block_maps;

  public static $definition = array(
    'table' => 'contactform',
    'primary' => 'id_contactform',
    'multilang' => true,
    'multilang_shop' => true,
    'fields' => array(

      //basic fields
      'date_add' =>    array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
      'email' =>     array('type' => self::TYPE_STRING,  'validate' => 'isString'),
      'background' =>     array('type' => self::TYPE_STRING,  'validate' => 'isString'),
      'color' =>     array('type' => self::TYPE_STRING,  'validate' => 'isString'),
      'block_description' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'block_form' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'block_image' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'block_maps' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'position_description' =>     array('type' => self::TYPE_STRING,  'validate' => 'isString'),
      'position_form' =>     array('type' => self::TYPE_STRING,  'validate' => 'isString'),
      'position_image' =>     array('type' => self::TYPE_STRING,  'validate' => 'isString'),
      'position_maps' =>     array('type' => self::TYPE_STRING,  'validate' => 'isString'),
      'name_field' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'email_field' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'phone_field' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'subject_field' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'captcha_field' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'attach_field' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'name_field_required' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'email_field_required' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'phone_field_required' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'subject_field_required' => 		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
      'background_button' =>     array('type' => self::TYPE_STRING,  'validate' => 'isString'),
      'background_button_hover' =>     array('type' => self::TYPE_STRING,  'validate' => 'isString'),
      'color_button' =>     array('type' => self::TYPE_STRING,  'validate' => 'isString'),
      'maps_code' =>     array('type' => self::TYPE_HTML,  'validate' => 'isString'),
      'width_maps' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'height_maps' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'width_description' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'width_form' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'width_image' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
      'width_maps_block' => 	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),

      // Lang fields
      'title_block_description' => 	array('type' => self::TYPE_STRING,  'lang' => true, 'validate' => 'isCleanHtml',  'size' => 512),
      'description' => 	array('type' => self::TYPE_HTML,  'lang' => true, 'validate' => 'isCleanHtml'),
      'title_block_form' =>			array('type' => self::TYPE_STRING,  'lang' => true, 'validate' => 'isCleanHtml',  'size' => 512),
      'title_block_image' =>			array('type' => self::TYPE_STRING,  'lang' => true, 'validate' => 'isCleanHtml',  'size' => 512),
      'title_block_maps' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml',  'size' => 512),
    )
  );

  public function __construct($id_contactform = null, $id_lang = null, $id_shop = null)
  {
    parent::__construct($id_contactform, $id_lang, $id_shop);
    Shop::addTableAssociation('contactform_lang', array('type' => 'fk_shop'));
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

  public function getContactForm($id_lang, $id_shop){
    $sql = '
			SELECT *
      FROM ' . _DB_PREFIX_ . 'contactform as c
      INNER JOIN ' . _DB_PREFIX_ . 'contactform_lang as cl
      ON c.id_contactform = cl.id_contactform
      WHERE cl.id_lang = ' . (int)$id_lang . '
      AND cl.id_shop = ' . (int)$id_shop . '
			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function validateField($field, $value, $id_lang = null, $skip = array(), $human_errors = false)
  {
    if ($field == 'email') {
      $emails = explode(',', $value);
      foreach($emails as $email){
        $email = trim($email);
        if(!Validate::isEmail($email)){
          $this->def['fields']['email']['validate'] = Tools::displayError('Email : Incorrect value');
        }
      }
    }
    return parent::validateField($field, $value, $id_lang, $skip, $human_errors);
  }

}