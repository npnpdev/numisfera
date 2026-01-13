<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.08.15
 * Time: 17:40
 */
require_once(dirname(__FILE__) . '/../../classes/homeContacts.php');

class AdminHomeContactsController extends ModuleAdminController
{
  private $_idShop;
  private $_idLang;
  private $_obj;
  protected $position_identifier = 'id_homecontacts';

  public function __construct()
  {
    $this->className = 'homeContacts';
    $this->table = 'homecontacts';
    $this->bootstrap = true;
    $this->lang = true;
    $this->edit = true;
    $this->delete = true;
    parent::__construct();
    $this->multishop_context = -1;
    $this->multishop_context_group = true;
    $this->position_identifier = 'id_homecontacts';
    $this->_idShop = Context::getContext()->shop->id;
    $this->_idLang = Context::getContext()->language->id;
    $this->_obj = new homeContacts();

    $this->bulk_actions = array(
      'delete' => array(
        'text' => $this->l('Delete selected'),
        'icon' => 'icon-trash',
        'confirm' => $this->l('Delete selected items?')
      )
    );

    $this->fields_list = array(
      'id_homecontacts' => array(
        'title' => $this->l('ID'),
        'search' => true,
        'onclick' => false,
        'filter_key' => 'a!id_homecontacts',
        'width' => 20
      ),
    );
  }

  public function init()
  {
    parent::init();
    if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive() && Tools::getValue('viewhomecontacts') === false)
      $this->_where = ' AND b.`id_shop` = '.(int)Context::getContext()->shop->id;
  }

  public function initProcess(){
    parent::initProcess();
  }

  public function renderList()
  {
    $this->addRowAction('edit');
    $this->addRowAction('delete');
    return parent::renderList();
  }


  public function renderForm()
  {

    $this->fields_form = array(
      'tinymce' => true,
      'legend' => array(
        'title' => $this->l('Settings'),
        'icon' => 'icon-cogs'
      ),
      'input'   => array(
        array(
          'type' => 'html',
          'name' => 'html_data',
          'form_group_class'=> 'block_data_settings',
          'html_content' => $this->l('Phone block'),
        ),
        array(
          'type'  => 'text',
          'label' => $this->l('Phone'),
          'name'  => 'phone',
          'lang'  => true,
          'required' => true,
        ),
        array(
          'type'         => 'textarea',
          'label'        => $this->l('Phone description'),
          'name'         => 'phone_description',
          'lang'         => true,
          'autoload_rte' => true,
          'rows'         => 10,
          'cols'         => 100,
          'hint'         => $this->l('Invalid characters:') . ' <>;=#{}'
        ),
        array(
          'type' => 'html',
          'name' => 'html_data',
          'form_group_class'=> 'block_data_settings',
          'html_content' => $this->l('Email block'),
        ),
        array(
          'type'  => 'text',
          'label' => $this->l('Email'),
          'name'  => 'email',
          'lang'  => true,
          'required' => true,
        ),
        array(
          'type'         => 'textarea',
          'label'        => $this->l('Email description'),
          'name'         => 'email_description',
          'lang'         => true,
          'autoload_rte' => true,
          'rows'         => 10,
          'cols'         => 100,
          'hint'         => $this->l('Invalid characters:') . ' <>;=#{}'
        ),
        array(
          'type' => 'html',
          'name' => 'html_data',
          'form_group_class'=> 'block_data_settings',
          'html_content' => $this->l('Working days block'),
        ),
        array(
          'type'  => 'text',
          'label' => $this->l('Working days'),
          'name'  => 'working_days',
          'lang'  => true,
          'required' => true,
        ),
        array(
          'type'         => 'textarea',
          'label'        => $this->l('Working days description'),
          'name'         => 'working_days_description',
          'lang'         => true,
          'autoload_rte' => true,
          'rows'         => 10,
          'cols'         => 100,
          'hint'         => $this->l('Invalid characters:') . ' <>;=#{}'
        ),
      ),
      'submit'  => array(
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

    $this->tpl_form_vars['lang_def'] = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
    $this->tpl_form_vars['PS_ALLOW_ACCENTED_CHARS_URL'] = (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');

    return parent::renderForm();
  }
}