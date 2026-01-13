<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.08.15
 * Time: 17:40
 */
require_once(dirname(__FILE__) . '/../../classes/topMenuColumn.php');


class AdminTopMenuColumnController extends ModuleAdminController
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


      if (Tools::getValue('action') == 'removeColumn') {
        $id_topmenu_column = Tools::getValue('id_topmenu_column');
        if($id_topmenu_column){
          $obj = new topMenuColumn($id_topmenu_column);
          $obj->delete();
        }
        $json['success'] = Module::getInstanceByName('mpm_topmenu')->l("Successfully saved!") ;
      }

      if (Tools::getValue('action') == 'updatePosition') {
        $replace = Tools::getValue('replace');
        $position = Tools::getValue('position');
        foreach ($position as $key => $value){
            $obj = new topMenuColumn($key);
            $obj->position = $value;
            $obj->ident = $value;
            $obj->save();
        }


        if($replace){
          $id_topmenu = Tools::getValue('id_topmenu');
          $columns = $this->getColumn(Tools::getValue('id_lang'), Tools::getValue('id_shop'), $id_topmenu);

          if($columns){
            $tpl = $this->getFormColumns($columns);
          }
          else{
            $tpl = $this->getFormColumn(1);
          }

          $json['tpl'] = $tpl;
        }


        $json['success'] = Module::getInstanceByName('mpm_topmenu')->l("Successfully saved!") ;
      }



      if (Tools::getValue('action') == 'saveColumn') {
        $id_topmenu_column = Tools::getValue('id_topmenu_column');
        $id_topmenu = Tools::getValue('id_topmenu');

        $ident = Tools::getValue('id');
        $title = Tools::getValue('title');
        $position = Tools::getValue('position');
        $active = Tools::getValue('active');

        $text_color = Tools::getValue('text_color');
        $text_color_hover = Tools::getValue('text_color_hover');
        $background_color = Tools::getValue('background_color');
        $width = Tools::getValue('width');
        $description_after = Tools::getValue('description_after');
        $description_before = Tools::getValue('description_before');
        $date_add =  date('Y-m-d H:i:s');

        if($id_topmenu_column){
          $obj = new topMenuColumn($id_topmenu_column);
        }
        else{
          $obj = new topMenuColumn();
        }

        $obj->id_topmenu = $id_topmenu;
        $obj->ident = $ident;
        $obj->title = $title;
        $obj->position = $position;
        $obj->active = $active;
        $obj->text_color = $text_color;
        $obj->text_color_hover = $text_color_hover;
        $obj->background_color = $background_color;
        $obj->width = $width;
        $obj->description_after = $description_after;
        $obj->description_before = $description_before;
        $obj->date_add = $date_add;

        $obj->save();

        $json['id_topmenu_column'] = $obj->id;
        $json['success'] = Module::getInstanceByName('mpm_topmenu')->l("Successfully saved!") ;
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

  public function getColumn($id_lang, $id_shop, $id_topmenu){

    $sql = '
			SELECT *
      FROM ' . _DB_PREFIX_ . 'topmenu_column as t
      INNER JOIN ' . _DB_PREFIX_ . 'topmenu_column_lang as tl
      ON t.id_topmenu_column = tl.id_topmenu_column
      WHERE tl.id_lang = ' . (int)$id_lang . '
      AND tl.id_shop = ' . (int)$id_shop .'
      AND t.id_topmenu = ' . (int)$id_topmenu .'
      ORDER BY t.position

			';

    return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
  }


  public function getFormColumns($columns){

    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'mpm_topmenu/views/templates/hook/columns.tpl');

    foreach ($columns as $key => $column){
      $obj = new topMenuColumn($column['id_topmenu_column']);

      $columns[$key]['text_after'] = $obj->description_after;
      $columns[$key]['text_before'] = $obj->description_before;

    }

    $languages = Language::getLanguages(false);
    $data->assign(
      array(
        'columns'  => $columns,
        'ajax'  => true,
        'languages'  => $languages,
        'defaultFormLanguage' => $this->default_form_language,
      )
    );
    return $data->fetch();

  }

  public function getFormColumn($id){
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'mpm_topmenu/views/templates/hook/tab_column.tpl');
    $languages = Language::getLanguages(false);
    $data->assign(
      array(
        'id'  => $id,
        'languages'  => $languages,
        'defaultFormLanguage' => $this->default_form_language,
      )
    );
    return $data->fetch();
  }

}