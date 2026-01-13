<?php
/**
 * Created by JetBrains PhpStorm.
 * User: YURA
 * Date: 28.05.15
 * Time: 15:38
 * To change this template use File | Settings | File Templates.
 */
class blogDataModel{

  private $_context;
  public function __construct(){
    include_once(dirname(__FILE__).'/../../config/config.inc.php');
    include_once(dirname(__FILE__).'/../../init.php');
    $this->_context = Context::getContext();
  }

  public function searchProduct( $id_shop = false, $id_lang  = false, $search = false )
  {
    if($id_shop === false){
      $id_shop =  $this->_context->shop->id ;
    }
    if($id_lang === false){
      $id_lang =  $this->_context->language->id ;
    }
    $where = "";
    if( $search ){
      $where = " AND (pl.name LIKE '%".pSQL($search)."%' OR p.id_product LIKE '%".pSQL($search)."%')";
    }
    $sql = '
			SELECT p.id_product, pl.name
      FROM ' . _DB_PREFIX_ . 'product_lang as pl
      LEFT JOIN ' . _DB_PREFIX_ . 'product as p
      ON p.id_product = pl.id_product
      WHERE pl.id_lang = ' . (int)$id_lang . '
      AND pl.id_shop = ' . (int)$id_shop . '
      ' . $where . '
      ORDER BY pl.name
      LIMIT 0,50
			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function showCheckedProducts( $id_shop = false, $id_lang  = false, $products_check = false )
  {
    if($id_shop === false){
      $id_shop =  $this->_context->shop->id ;
    }
    if($id_lang === false){
      $id_lang =  $this->_context->language->id ;
    }

    $where = "";
    $limit = "  LIMIT 300 ";
    if( $products_check !== false ){
      if( !$products_check ){
        return array();
      }
      $products_check = implode(",", $products_check);
      $where = " AND p.id_product  IN ($products_check) ";
      $limit = "";
    }
    $sql = '
			SELECT p.id_product, pl.name
      FROM ' . _DB_PREFIX_ . 'product_lang as pl
      LEFT JOIN ' . _DB_PREFIX_ . 'product as p
      ON p.id_product = pl.id_product
      WHERE pl.id_lang = ' . (int)$id_lang . '
      AND pl.id_shop = ' . (int)$id_shop . '
      ' . $where . '
      ORDER BY pl.name
      ' . $limit . '
			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function searchPost( $search = false, $id_shop = false, $id_lang  = false, $excludedPost = false )
  {
    if($id_shop === false){
      $id_shop =  $this->_context->shop->id ;
    }
    if($id_lang === false){
      $id_lang =  $this->_context->language->id ;
    }

    $where = "";
    if( $search ){
      $where .= " AND (bpl.name LIKE '%".pSQL($search)."%' OR bpl.id_blog_post LIKE '%".pSQL($search)."%')";
    }
    if( $excludedPost ){
      $where .= ' AND bpl.id_blog_post != '. $excludedPost;
    }

    $sql = '
			SELECT bpl.id_blog_post, bpl.name
      FROM ' . _DB_PREFIX_ . 'blog_post_lang as bpl
      WHERE bpl.id_shop = ' . (int)$id_shop . '
      AND bpl.id_lang = ' . (int)$id_lang . '
      ' . $where . '
      ORDER BY bpl.name
      LIMIT 0,50
			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }

  public function showCheckedPost( $items_check = false , $id_shop = false, $id_lang  = false)
  {
    if($id_shop === false){
      $id_shop =  $this->_context->shop->id ;
    }
    if($id_lang === false){
      $id_lang =  $this->_context->language->id ;
    }

    $where = "";
    $limit = "  LIMIT 300 ";
    if( $items_check !== false ){
      if( !$items_check ){
        return array();
      }
      $items_check = implode(",", $items_check);
      $where = " AND bpl.id_blog_post  IN ($items_check) ";
      $limit = "";
    }
    $sql = '
			SELECT bpl.id_blog_post, bpl.name
      FROM ' . _DB_PREFIX_ . 'blog_post_lang as bpl
      WHERE bpl.id_shop = ' . (int)$id_shop . '
      AND bpl.id_lang = ' . (int)$id_lang . '
      ' . $where . '
       ORDER BY bpl.name
      ' . $limit . '
			';
    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }
}