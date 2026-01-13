<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.08.15
 * Time: 17:40
 */
require_once(dirname(__FILE__) . '/../../classes/homeBanners.php');

class AdminBannersController extends ModuleAdminController
{
  private $_idShop;
  private $_idLang;
  private $_obj;
  protected $position_identifier = 'id_banners';

  public function __construct()
  {
    $this->className = 'homeBanners';
    $this->table = 'banners';
    $this->bootstrap = true;
    $this->lang = true;
    $this->edit = true;
    $this->delete = true;
    parent::__construct();
    $this->multishop_context = -1;
    $this->multishop_context_group = true;
    $this->position_identifier = 'id_banners';
    $this->_defaultOrderBy = 'a!position';
    $this->orderBy = 'position';
    $this->_imgDir = _PS_MODULE_DIR_ . 'mpm_banners/views/img/';
    $this->_idShop = Context::getContext()->shop->id;
    $this->_idLang = Context::getContext()->language->id;
    $this->_obj = new homeBanners();

    $this->bulk_actions = array(
      'delete' => array(
        'text' => $this->l('Delete selected'),
        'icon' => 'icon-trash',
        'confirm' => $this->l('Delete selected items?')
      )
    );

    $this->fields_list = array(
      'id_banners' => array(
        'title' => $this->l('ID'),
        'search' => true,
        'onclick' => false,
        'filter_key' => 'a!id_banners',
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

  public function getQuestionStrip($question)
  {
    return Tools::truncate($question, 30, '...');
  }


  public function init()
  {
    parent::init();
    if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive() && Tools::getValue('viewbanners') === false)
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

    $hook = array(
      array(
        'id' => 'displayHomeContent1',
        'val' => 'displayHomeContent1',
        'name' => $this->l('displayHomeContent1')
      ),
      array(
        'id' => 'displayHomeContent2',
        'val' => 'displayHomeContent2',
        'name' => $this->l('displayHomeContent2')
      ),
      array(
        'id' => 'displayHomeContent3',
        'val' => 'displayHomeContent3',
        'name' => $this->l('displayHomeContent3')
      ),
      array(
        'id' => 'displayHomeContent4',
        'val' => 'displayHomeContent4',
        'name' => $this->l('displayHomeContent4')
      ),
      array(
        'id' => 'displayHomeContent5',
        'val' => 'displayHomeContent5',
        'name' => $this->l('displayHomeContent5')
      ),
    );

    $ext = 'png';
    $obj = $this->loadObject(true);
    $image = $this->_imgDir.$obj->id.'_left.'.$ext;
    $image_url_left = ImageManager::thumbnail($image, $this->table.'_'.(int)$obj->id.'_left.'.$ext, 350, $ext, true, true);
    $image_size_left = file_exists($image) ? filesize($image) / 1000 : false;



    $image = $this->_imgDir.$obj->id.'_right.'.$ext;
    $image_url_right = ImageManager::thumbnail($image, $this->table.'_'.(int)$obj->id.'_right.'.$ext, 350, $ext, true, true);
    $image_size_right = file_exists($image) ? filesize($image) / 1000 : false;




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
          'type' => 'select',
          'label' => $this->l('Hook'),
          'name' => 'hook',
          'class' => '',
          'options' => array(
            'query' => $hook,
            'id' => 'id',
            'name' => 'name'
          )
        ),
        array(
          'type'  => 'text',
          'label' => $this->l('Min height block'),
          'name'  => 'min_height',
          'required' => true,
          'desc' => $this->l('Min height in "px".')
        ),
        array(
          'type' => 'html',
          'name' => 'html_data',
          'form_group_class'=> 'block_data_settings',
          'html_content' => $this->l('Left block'),
        ),
        array(
          'type'              => 'file',
          'label'             => $this->l('Background'),
          'form_group_class'  => 'uploadImagesForm',
          'image'             => $image_url_left ? $image_url_left : false,
          'name'              => 'image_left',
          'size'              => $image_size_left,
          'delete_url'        => self::$currentIndex.'&'.$this->identifier.'='.(int)$obj->id.'&updatebanners&token='.$this->token.'&deleteImage_left=1',
        ),
        array(
          'type' => 'color',
          'label' => $this->l('Background color'),
          'name' => 'background_color_left',
          'hint' => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").')
        ),
        array(
          'type'         => 'textarea',
          'label'        => $this->l('Description left block'),
          'name'         => 'description_left',
          'lang'         => true,
          'autoload_rte' => true,
          'rows'         => 10,
          'cols'         => 100,
          'hint'         => $this->l('Invalid characters:') . ' <>;=#{}'
        ),
        array(
          'type'  => 'text',
          'label' => $this->l('Width left block'),
          'name'  => 'width_block_left',
          'required' => true,
          'desc' => $this->l('Width block in "%".')
        ),
        array(
          'type'  => 'text',
          'label' => $this->l('Width description left block'),
          'name'  => 'width_description_left',
          'required' => true,
          'desc' => $this->l('Width description in "px".')
        ),
        array(
          'type' => 'select',
          'label' => $this->l('Position description left block'),
          'name' => 'position_description_left',
          'class' => '',
          'options' => array(
            'query' => array(
              array(
                'id' => 'left',
                'val' => 'left',
                'name' => $this->l('Left')
              ),
              array(
                'id' => 'center',
                'val' => 'center',
                'name' => $this->l('Center')
              ),
              array(
                'id' => 'right',
                'val' => 'right',
                'name' => $this->l('Right')
              ),
            ),
            'id' => 'id',
            'name' => 'name'
          )
        ),
        array(
          'type' => 'html',
          'name' => 'html_data',
          'form_group_class'=> 'block_data_settings',
          'html_content' => $this->l('Right block'),
        ),
        array(
          'type'              => 'file',
          'label'             => $this->l('Background'),
          'form_group_class'  => 'uploadImagesForm',
          'image'             => $image_url_right ? $image_url_right : false,
          'name'              => 'image_right',
          'size'              => $image_size_right,
          'delete_url'        => self::$currentIndex.'&'.$this->identifier.'='.(int)$obj->id.'&updatebanners&token='.$this->token.'&deleteImage_right=1',
        ),
        array(
          'type' => 'color',
          'label' => $this->l('Background color'),
          'name' => 'background_color_right',
          'hint' => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").')
        ),
        array(
          'type'         => 'textarea',
          'label'        => $this->l('Description right block'),
          'name'         => 'description_right',
          'lang'         => true,
          'autoload_rte' => true,
          'rows'         => 10,
          'cols'         => 100,
          'hint'         => $this->l('Invalid characters:') . ' <>;=#{}'
        ),
        array(
          'type'  => 'text',
          'label' => $this->l('Width right block '),
          'name'  => 'width_block_right',
          'required' => true,
          'desc' => $this->l('Width block in "%".')
        ),
        array(
          'type'  => 'text',
          'label' => $this->l('Width description right block'),
          'name'  => 'width_description_right',
          'required' => true,
          'desc' => $this->l('Width description in "px".')
        ),
        array(
          'type' => 'select',
          'label' => $this->l('Position description right block'),
          'name' => 'position_description_right',
          'class' => '',
          'options' => array(
            'query' => array(
              array(
                'id' => 'left',
                'val' => 'left',
                'name' => $this->l('Left')
              ),
              array(
                'id' => 'center',
                'val' => 'center',
                'name' => $this->l('Center')
              ),
              array(
                'id' => 'right',
                'val' => 'right',
                'name' => $this->l('Right')
              ),
            ),
            'id' => 'id',
            'name' => 'name'
          )
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
    $res = $this->uploadImages($id.'_left', 'image_left', $this->_imgDir);
    $res = $this->uploadImages($id.'_right', 'image_right', $this->_imgDir);
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
    if( Tools::getValue('deleteImage_left') ){
      if (Validate::isLoadedObject($object = $this->loadObject())){
        unlink($this->_imgDir.$object->id.'_left.png');
      }
    }

    if( Tools::getValue('deleteImage_right') ){
      if (Validate::isLoadedObject($object = $this->loadObject())){
        unlink($this->_imgDir.$object->id.'_right.png');
      }
    }
    return parent::postProcess();
  }

  public function ajaxProcessUpdatePositions()
  {
    $product_info = Tools::getValue('banners');
    foreach($product_info as $key => $value){
      $value = explode('_', $value);
      Db::getInstance()->update('banners', array('position' => (int)$key), 'id_banners='.(int)$value[2]);
    }

  }

}