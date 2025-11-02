<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.08.15
 * Time: 17:40
 */
require_once(dirname(__FILE__) . '/../../classes/topMenuLink.php');


class AdminTopMenuLinkController extends ModuleAdminController
{
  private $_idShop;
  private $_idLang;


  public function __construct()
  {

    parent::__construct();

    $this->_idShop = Context::getContext()->shop->id;
    $this->_idLang = Context::getContext()->language->id;

  }



  public function displayAjax()
  {
    $json = array();
    try{

      if (Tools::getValue('action') == 'removeLink') {
        $id = Tools::getValue('id');
        $obj = new topMenuLink($id);
        $obj->delete();
        $json['success'] = Module::getInstanceByName('mpm_topmenu')->l("Successfully saved!");
      }

      if (Tools::getValue('action') == 'saveLink') {

        $id_lang = Tools::getValue('id_lang');
        $id_shop = Tools::getValue('id_shop');
        $id_link = Tools::getValue('id_link');
        $ids_link = trim(Tools::getValue('ids_link'));
        $date_add =  date('Y-m-d H:i:s');

        $link = Tools::getValue('link');
        $rez = '';

        $title = array();
        $url = array();

        foreach ($link as $key => $value){

          $data = explode("_", $key);

          if($data[0] == 'titlelink'){
            if($data[1]){
              $title[$data[1]] = $value;
            }
            else{
              $title = $value;
            }
          }

          if($data[0] == 'url' && $data[1]){
            if($data[1]){
              $url[$data[1]] = $value;
            }
            else{
              $url = $value;
            }
          }

        }

        if($id_link){
          $obj = new topMenuLink($id_link);
        }
        else{
          $obj = new topMenuLink();
        }

        $obj->title = $title;
        $obj->link = $url;
        $obj->date_add = $date_add;
        $obj->save();



        if($ids_link){
          $ids = explode(",", trim($ids_link));
          if(!$id_link){
            $ids[] = $obj->id;
          }
          $rez = implode(",", $ids);
        }
        else{
          $rez = $obj->id;
        }

        $json['list'] = $this->getLinkList(0, $id_lang, $id_shop, $rez);
        $json['ids_link'] = $rez;



        $json['success'] = Module::getInstanceByName('mpm_topmenu')->l("Successfully saved!");
      }


      die( json_encode($json) );
    }
    catch(Exception $e){
      $json['error'] = $e->getMessage();

      if( $e->getCode() == 10 ){
        $json['error_message'] = $e->getMessage();
      }
    }
    die( json_encode($json) );
  }

  public function getLinkList($ident, $id_lang, $id_shop, $ids){

    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'mpm_topmenu/views/templates/hook/linkList.tpl');

    $links = false;


    if($ids){
      $ids = explode(",", $ids);

      foreach ($ids as $val){
       $obj = new topMenuLink($val, $id_lang, $id_shop);
       $links[] = array(
         'title' => $obj->title,
         'url' => $obj->link,
         'id' => $obj->id_topmenu_link,
       );
      }
    }

    $data->assign(
      array(
        'id_shop' => $id_shop,
        'id_lang' => $id_lang,
        'ident'   => $ident,
        'items'   => $links,
      )
    );

    return $data->fetch();
  }

}