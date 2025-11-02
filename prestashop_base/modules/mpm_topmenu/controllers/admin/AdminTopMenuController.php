<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.08.15
 * Time: 17:40
 */
require_once(dirname(__FILE__) . '/../../classes/topMenu.php');
require_once(dirname(__FILE__) . '/../../classes/topMenuColumn.php');

class AdminTopMenuController extends ModuleAdminController
{
  private $_idShop;
  private $_idLang;
  protected $position_identifier = 'id_topmenu';

  public function __construct()
  {
    $this->className = 'topMenu';
    $this->table = 'topmenu';
    $this->bootstrap = true;
    $this->lang = true;
    $this->edit = true;
    $this->delete = true;
    parent::__construct();
    $this->multishop_context = -1;
    $this->multishop_context_group = true;
    $this->position_identifier = 'id_topmenu';
    $this->_defaultOrderBy = 'a!position';
    $this->orderBy = 'position';

    $this->_idShop = Context::getContext()->shop->id;
    $this->_idLang = Context::getContext()->language->id;

    $this->bulk_actions = array(
      'delete' => array(
        'text' => $this->l('Delete selected'),
        'icon' => 'icon-trash',
        'confirm' => $this->l('Delete selected items?')
      )
    );

    $this->fields_list = array(
      'id_topmenu' => array(
        'title' => $this->l('ID'),
        'search' => true,
        'onclick' => false,
        'filter_key' => 'a!id_topmenu',
        'width' => 20
      ),
      'title' => array(
        'title' => $this->l('Title'),
        'filter_key' => 'b!title',
        'search' => true,
        'width' =>100,
        'align' => 'left',
      ),
      'position' => array(
        'title' => $this->l('Position'),
        'width' => 40,
        'search' => false,
        'filter_key' => 'a!position',
        'align' => 'left',
        'position' => 'position'
      ),
      'active' => array(
        'title' => $this->l('Displayed'),
        'search' => true,
        'active' => 'status',
        'type' => 'bool',
        'width' => 20,
      ),
      'date_add' => array(
        'title' => $this->l('Date add'),
        'maxlength' => 190,
        'width' =>100,
        'align' => 'left',
      )
    );
  }

  public function init()
  {
    parent::init();
    if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive() && Tools::getValue('viewtopmenu') === false)
      $this->_where = ' AND b.`id_shop` = '.(int)Context::getContext()->shop->id;
  }

  public function initProcess(){
    parent::initProcess();
  }

  public function initContent()
  {

    parent::initContent();
  }

  public function renderList()
  {
    $this->addRowAction('edit');
    $this->addRowAction('delete');
    return parent::renderList();
  }

  public function postProcess()
  {
    return parent::postProcess();
  }

  public function renderForm(){

    $obj = $this->loadObject(true);
    $columns = $this->getColumn($this->_idLang, $this->_idShop, $obj->id);

    if($columns){
      $tpl = $this->getFormColumns($columns);
    }
    else{
      $tpl = $this->getFormColumn(1);
    }

    $this->fields_form = array(
      'legend' => array(
        'title' => $this->l('Settings'),
        'icon' => 'icon-cogs'
      ),
      'input' => array(
        array(
          'type' => 'html',
          'form_group_class' => 'tabs_content',
          'name' => '<div class="tabs">
                        <a data-class="tabs_block"  class="tab_item active">'.$this->l('Tab settings').'</a>
                        <a data-class="add_column"  class="tab_item">'.$this->l('Add column').'</a>
                        <a data-class="add_item_group"  class="tab_add_item_group tab_item">'.$this->l('Add item group').'</a>
                     </div>',
        ),
        array(
          'type'             => 'html',
          'name'             => 'html_data',
          'form_group_class' => 'block_info_settings tab_content tabs_block',
          'html_content'     => $this->informBlock($this->l('Before add columns you must save tab settings!')),
        ),
        array(
          'type'    => 'switch',
          'label'   => $this->l('Active'),
          'name'    => 'active',
          'form_group_class' => 'tab_content tabs_block',
          'is_bool' => true,
          'values'  => array(
            array(
              'id'    => 'active_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id'    => 'active_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type'     => 'text',
          'label'    => $this->l('Title'),
          'name'     => 'title',
          'form_group_class' => 'tab_content tabs_block',
          'required' => true,
          'lang'     => true,
        ),
        array(
          'type'  => 'text',
          'label' => $this->l('Link'),
          'name'  => 'link',
          'form_group_class' => 'tab_content tabs_block',
          'lang'  => true,
        ),
        array(
          'type'    => 'switch',
          'label'   => $this->l('Open new window'),
          'name'    => 'open_new_window',
          'form_group_class' => 'tab_content tabs_block',
          'is_bool' => true,
          'values'  => array(
            array(
              'id'    => 'active_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id'    => 'active_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type'  => 'color',
          'label' => $this->l('Text color'),
          'name'  => 'text_color_tab',
          'form_group_class' => 'tab_content tabs_block',
          'hint'  => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").')
        ),
        array(
          'type'  => 'color',
          'label' => $this->l('Text color hover'),
          'name'  => 'text_color_hover_tab',
          'form_group_class' => 'tab_content tabs_block',
          'hint'  => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").')
        ),
        array(
          'type'  => 'color',
          'label' => $this->l('Background color'),
          'name'  => 'background_color_tab',
          'form_group_class' => 'tab_content tabs_block',
          'hint'  => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").')
        ),
        array(
          'type'  => 'color',
          'label' => $this->l('Background color hover'),
          'name'  => 'background_color_hover_tab',
          'form_group_class' => 'tab_content tabs_block',
          'hint'  => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").')
        ),
        array(
          'type'             => 'html',
          'name'             => 'html_data',
          'form_group_class' => 'block_data_settings tab_content tabs_block',
          'html_content'     => $this->l('Drop-down list settings'),
        ),
        array(
          'type'             => 'text',
          'label'            => $this->l('Width (px)'),
          'name'             => 'width',
          'form_group_class' => 'block_settings_number tab_content tabs_block',
          'desc'             => $this->l('(Put 0 for automatic width)')
        ),
        array(
          'type'             => 'text',
          'label'            => $this->l('Min height (px)'),
          'name'             => 'min_height',
          'form_group_class' => 'block_settings_number tab_content tabs_block',
          'desc'             => $this->l('(Put 0 for automatic width)')
        ),
        array(
          'type'             => 'text',
          'label'            => $this->l('Border size'),
          'name'             => 'border_size',
          'tab'              => 'tab_settings',
          'form_group_class' => 'block_settings_number tab_content tabs_block',
        ),
        array(
          'type'  => 'color',
          'label' => $this->l('Border color'),
          'name'  => 'border_color',
          'form_group_class' => 'tab_content tabs_block',
          'hint'  => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").')
        ),
        array(
          'type'  => 'color',
          'label' => $this->l('Background color'),
          'name'  => 'background_color',
          'form_group_class' => 'tab_content tabs_block',
          'hint'  => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").')
        ),
        array(
          'type'         => 'textarea',
          'label'        => $this->l('Text displayed before columns'),
          'name'         => 'description_before',
          'lang'         => true,
          'autoload_rte' => true,
          'rows'         => 10,
          'cols'         => 100,
          'form_group_class' => 'tab_content tabs_block',
          'hint'         => $this->l('Invalid characters:') . ' <>;=#{}'
        ),
        array(
          'type'         => 'textarea',
          'label'        => $this->l('Text displayed after columns'),
          'name'         => 'description_after',
          'lang'         => true,
          'autoload_rte' => true,
          'rows'         => 10,
          'cols'         => 100,
          'form_group_class' => 'tab_content tabs_block',
          'hint'         => $this->l('Invalid characters:') . ' <>;=#{}'
        ),
        array(
          'type'             => 'html',
          'name'             => 'html_data',
          'form_group_class' => 'tab_content add_column block_info_settings',
          'html_content'     => $this->informBlock($this->l('Before add column you must save tab settings!')),
        ),
        array(
          'type'             => 'html',
          'name'             => 'html_data',
          'form_group_class' => 'tab_content add_column getFormColumn',
          'html_content'     => $tpl,
        ),
        array(
          'type'             => 'html',
          'name'             => 'html_data',
          'form_group_class' => 'tab_content add_item_group block_info_settings',
          'html_content'     => $this->informBlock($this->l('Before add item group you must save tab and add column!')),
        ),
        array(
          'type'             => 'html',
          'name'             => 'html_data',
          'form_group_class' => 'tab_content add_item_group getFormGroup',
          'html_content'     => $this->getFormGroup(),
        ),
        array(
          'type' => 'hidden',
          'name' => 'idLang',
        ),
        array(
          'type' => 'hidden',
          'name' => 'idShop',
        ),
        array(
          'type' => 'hidden',
          'name' => 'token_top',
        ),
        array(
          'type' => 'hidden',
          'name' => 'token_top_column',
        ),
        array(
          'type' => 'hidden',
          'name' => 'token_top_group',
        ),
        array(
          'type' => 'hidden',
          'name' => 'token_top_link',
        ),
      ),
      'submit' => array(
        'title' => $this->l('Save'),
      ),
      'buttons' => array(
        'save-and-stay' => array(
          'title' => $this->l('Save and stay'),
          'name'  => 'submitAdd' . $this->table . 'AndStay',
          'type'  => 'submit',
          'class' => 'btn btn-default pull-right',
          'icon'  => 'process-icon-save'
        ),
      ),
    );

    $this->fields_value['token_top'] = Tools::getAdminTokenLite('AdminTopMenu');
    $this->fields_value['token_top_column'] = Tools::getAdminTokenLite('AdminTopMenuColumn');
    $this->fields_value['token_top_group'] = Tools::getAdminTokenLite('AdminTopMenuGroup');
    $this->fields_value['token_top_link'] = Tools::getAdminTokenLite('AdminTopMenuLink');
    $this->fields_value['idLang'] = $this->_idLang;
    $this->fields_value['idShop'] = $this->_idShop;
    $this->tpl_form_vars['PS_ALLOW_ACCENTED_CHARS_URL'] = (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');

    return parent::renderForm();
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


  public function getFormGroup(){
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'mpm_topmenu/views/templates/hook/tab_groups.tpl');
    return $data->fetch();
  }


  public function getColumnButtons($id){
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'mpm_topmenu/views/templates/hook/button_column.tpl');

    $data->assign(
      array(
        'id'  => $id,
      )
    );
    return $data->fetch();
  }


  public function informBlock($msg){
    $data = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'mpm_topmenu/views/templates/hook/info.tpl');

    $data->assign(
      array(
        'msg'  => $msg,
      )
    );
    return $data->fetch();
  }


  public function ajaxProcessUpdatePositions()
  {
    $product_info = Tools::getValue('topmenu');
    foreach($product_info as $key => $value){
      $value = explode('_', $value);
      Db::getInstance()->update('topmenu', array('position' => (int)$key), 'id_topmenu='.(int)$value[2]);
    }

  }


  public function displayAjax()
  {
    $json = array();
    try{

      if (Tools::getValue('action') == 'addNewColumn') {
        $id = Tools::getValue('id');
        $json['form'] = $this->getFormColumn($id);
      }


      if (Tools::getValue('action') == 'addNewGroup') {
        $id = Tools::getValue('id');
        $json['form'] = $this->getFormColumn($id);
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
        'languages'  => $languages,
        'ajax'  => false,
        'defaultFormLanguage' => $this->default_form_language,
      )
    );
    return $data->fetch();

  }


}