<?php

class homeContacts extends ObjectModel
{
  public $id_homecontacts;
  public $phone;
  public $phone_description;
  public $email;
  public $email_description;
  public $working_days;
  public $working_days_description;
  public $hook = 'displayHomeContent5';

  public static $definition = array(
    'table' => 'homecontacts',
    'primary' => 'id_homecontacts',
    'multilang' => true,
    'multilang_shop' => true,
    'fields' => array(
      //basic fields

      'hook' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),

      // Lang fields
      'phone' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'required' => true, 'lang' => true),
      'phone_description' =>			array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'lang' => true),
      'email' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'required' => true, 'lang' => true),
      'email_description' =>			array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'lang' => true),
      'working_days' =>			array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'required' => true, 'lang' => true),
      'working_days_description' =>			array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml',  'lang' => true),

    )
  );

  public function __construct($id_homecontacts = null, $id_lang = null, $id_shop = null)
  {
    parent::__construct($id_homecontacts, $id_lang, $id_shop);
    Shop::addTableAssociation('homecontacts', array('type' => 'fk_shop'));
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

  public function getHomeContacts($id_lang, $id_shop){
    $sql = '
      SELECT  *
        FROM ' . _DB_PREFIX_ . 'homecontacts c
        LEFT JOIN ' . _DB_PREFIX_ . 'homecontacts_lang as cl
        ON c.id_homecontacts = cl.id_homecontacts
        WHERE cl.id_lang = ' . $id_lang . '
        AND cl.id_shop = '.$id_shop.'
			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

}