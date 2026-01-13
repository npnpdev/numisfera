<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.08.15
 * Time: 17:40
 */
require_once(dirname(__FILE__) . '/../../classes/homeBlocks.php');

class AdminBlocksHomeController extends ModuleAdminController
{
  private $_idShop;
  private $_idLang;
  private $_obj;
  protected $position_identifier = 'id_homeblocks';

  public function __construct()
  {
    $this->className = 'homeBlocks';
    $this->table = 'homeblocks';
    $this->bootstrap = true;
    $this->lang = true;
    $this->edit = true;
    $this->delete = true;
    parent::__construct();
    $this->multishop_context = -1;
    $this->multishop_context_group = true;
    $this->position_identifier = 'id_homeblocks';
    $this->_defaultOrderBy = 'a!position';
    $this->orderBy = 'position';
    $this->_imgDir = _PS_MODULE_DIR_ . 'mpm_homeblocks/views/img/';
    $this->_idShop = Context::getContext()->shop->id;
    $this->_idLang = Context::getContext()->language->id;
    $this->_obj = new homeBlocks();

    $this->bulk_actions = array(
      'delete' => array(
        'text' => $this->l('Delete selected'),
        'icon' => 'icon-trash',
        'confirm' => $this->l('Delete selected items?')
      )
    );

    $this->fields_list = array(
      'id_homeblocks' => array(
        'title' => $this->l('ID'),
        'search' => true,
        'onclick' => false,
        'filter_key' => 'a!id_homeblocks',
        'width' => 20
      ),
            'active' => array(
        'title' => $this->l('Displayed'),
        'search' => true,
        'active' => 'status',
        'type' => 'bool',
        'width' => 20,
      ),
      'position' => array(
        'title' => $this->l('Position'),
        'width' => 40,
        'search' => false,
        'filter_key' => 'a!position',
        'align' => 'left',
        'position' => 'position'
      ),
      'date_add' => array(
        'title' => $this->l('Date add'),
        'maxlength' => 190,
        'width' =>100,
        'align' => 'left',
        'search' => false,
      )
    );
  }


  public function init()
  {
    parent::init();
    if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive() && Tools::getValue('viewhomeblocks') === false)
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

    $ext = 'png';
    $obj = $this->loadObject(true);
    $image = $this->_imgDir.$obj->id.'.'.$ext;
    $image_url_left = ImageManager::thumbnail($image, $this->table.'_'.(int)$obj->id.'.'.$ext, 350, $ext, true, true);
    $image_size_left = file_exists($image) ? filesize($image) / 1000 : false;

    $this->fields_form = array(
      'tinymce' => true,
      'legend' => array(
        'title' => $this->l('Settings'),
        'icon' => 'icon-cogs'
      ),
      'input'   => array(
        array(
          'type' => 'switch',
          'label' => $this->l('Active'),
          'name' => 'active',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'active_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'active_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type'  => 'text',
          'label' => $this->l('Width'),
          'name'  => 'width',
          'required' => true,
          'desc' => $this->l('Width block in "%".')
        ),
        array(
          'type'  => 'text',
          'label' => $this->l('Min height'),
          'name'  => 'min_height',
          'required' => true,
          'desc' => $this->l('Min height in "px".')
        ),
        array(
          'type'              => 'file',
          'label'             => $this->l('Background'),
          'form_group_class'  => 'uploadImagesForm',
          'image'             => $image_url_left ? $image_url_left : false,
          'name'              => 'image_left',
          'size'              => $image_size_left,
          'delete_url'        => self::$currentIndex.'&'.$this->identifier.'='.(int)$obj->id.'&updatehomeblocks&token='.$this->token.'&deleteImage=1',
        ),
        array(
          'type' => 'color',
          'label' => $this->l('Background color'),
          'name' => 'background_color',
          'hint' => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").')
        ),
        array(
          'type'         => 'textarea',
          'label'        => $this->l('Description left block'),
          'name'         => 'description',
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


  protected function postImage($id)
  {
    $res = $this->uploadImages($id, 'image_left', $this->_imgDir);
    return $res;
  }

  protected function uploadImages($id, $name, $dir)
  {
    if (isset($_FILES[$name]['tmp_name']) && !empty($_FILES[$name]['tmp_name']))
    {
      $max_size = isset($this->maxImageSize) ? $this->maxImageSize : 0;
      if (ImageManager::validateUpload($_FILES[$name], Tools::getMaxUploadSize($max_size))){
      }
      elseif (!($tmpName = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES[$name]['tmp_name'], $tmpName)){
        return false;
      }
      else
      {
        $_FILES[$name]['tmp_name'] = $tmpName;
        if (!ImageManager::resize($tmpName, $dir.$id.'.png')){
          return false;
        }
        unlink($tmpName);
      }
    }
    return true;
  }

  public function postProcess()
  {
    if( Tools::getValue('deleteImage') ){
      if (Validate::isLoadedObject($object = $this->loadObject())){
        unlink($this->_imgDir.$object->id.'.png');
      }
    }

    return parent::postProcess();
  }

  public function ajaxProcessUpdatePositions()
  {
    $product_info = Tools::getValue('homeblocks');
    foreach($product_info as $key => $value){
      $value = explode('_', $value);
      Db::getInstance()->update('homeblocks', array('position' => (int)$key), 'id_homeblocks='.(int)$value[2]);
    }

  }

}