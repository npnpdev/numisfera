<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.08.15
 * Time: 17:40
 */
require_once(dirname(__FILE__) . '/../../classes/blockHomeSlide.php');

class AdminHomeSliderController extends ModuleAdminController
{
  private $_imgDir;
  private $_images;
  private $_idShop;
  private $_idLang;
  private $_homeSlider;
  protected $position_identifier = 'id_block_home_slider';

  public function __construct()
  {
    $this->className = 'blockHomeSlide';
    $this->table = 'block_home_slider';
    $this->bootstrap = true;
    $this->lang = true;
    $this->edit = true;
    $this->delete = true;
    parent::__construct();
    $this->multishop_context = -1;
    $this->multishop_context_group = true;
    $this->position_identifier = 'id_block_home_slider';
    $this->_defaultOrderBy = 'a!position';
    $this->orderBy = 'position';
    $this->_imgDir = _PS_MODULE_DIR_ . 'mpm_homeslider/views/img/';
    $this->_images = _MODULE_DIR_ . 'mpm_homeslider/views/img/';
    $this->_idShop = Context::getContext()->shop->id;
    $this->_idLang = Context::getContext()->language->id;
    $this->_homeSlider = new blockHomeSlide();

    $this->bulk_actions = array(
      'delete' => array(
        'text' => $this->l('Delete selected'),
        'icon' => 'icon-trash',
        'confirm' => $this->l('Delete selected items?')
      )
    );

    $this->fields_list = array(
      'id_block_home_slider' => array(
        'title' => $this->l('ID'),
        'search' => true,
        'onclick' => false,
        'filter_key' => 'a!id_block_home_slider',
        'width' => 20
      ),
     'image' => array(
        'title' => $this->l('Image'),
        'align' => 'center',
        'width' => 20,
        'orderby' => false,
        'filter' => false,
        'search' => false,
        'align' => 'left',
        'callback' => 'getSliderImage',
      ),
      'title' => array(
        'title' => $this->l('Title'),
        'filter_key' => 'b!title',
        'search' => true,
        'width' =>100,
        'align' => 'left',
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
      )
    );
  }

  public function init()
  {
    parent::init();
    if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive() && Tools::getValue('viewblock_home_slider') === false)
      $this->_where = ' AND b.`id_shop` = '.(int)Context::getContext()->shop->id;
  }

  public function initProcess(){
    parent::initProcess();
  }

  public function initContent()
  {
    $settings = Tools::unserialize(Configuration::get('GOMAKOIL_HOME_SLIDER'));
    $form = $this->getFormGeneralSettings($settings);
    $this->tpl_list_vars['form'] = $form;
    $this->tpl_list_vars['token_admin'] = Tools::getAdminTokenLite('AdminHomeSlider');
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



  public function getFormGeneralSettings($settings){

    $desc = '';



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

    $this->fields_form = array(
      'tinymce' => true,
      'legend' => array(
        'title' => $this->l('Settings'),
        'icon' => 'icon-cogs'
      ),
      'input' => array(
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
          'type' => 'switch',
          'label' => $this->l('Auto play'),
          'name' => 'auto_play',
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
          'type' => 'text',
          'label' => $this->l('Width slider'),
          'name' => 'width',
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Height slider'),
          'name' => 'height',
          'form_group_class' => 'block_size_slider',
          'desc' =>  $desc,
        ),

        array(
          'type' => 'text',
          'label' => $this->l('Auto play speed'),
          'name' => 'speed',
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
          'name' => 'token_slider',
        ),
      ),
      'buttons' => array(
        'save' => array(
          'title' => $this->l('Save'),
          'name' => 'submitSaveGeneralSettings',
          'type' => 'submit',
          'class' => 'btn btn-default pull-right submitSaveGeneralSettings',
          'icon' => 'process-icon-save'
        ),
      ),
    );

   if(isset($settings) && $settings){
     foreach($settings as $key => $val){
       $this->fields_value[$key] = $val;
     }
   }

    $this->fields_value['idLang'] = $this->_idLang;
    $this->fields_value['idShop'] = $this->_idShop;
    $this->fields_value['token_slider'] = Tools::getAdminTokenLite('AdminHomeSlider');
    $this->tpl_form_vars['PS_ALLOW_ACCENTED_CHARS_URL'] = (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');

    return parent::renderForm();
  }

  public function renderForm()
  {
    $desc = '';
    $settings = Tools::unserialize(Configuration::get('GOMAKOIL_HOME_SLIDER'));



    $position = array(
      array(
        'id' => 'top_left',
        'name' => $this->l('Top left corner')
      ),
      array(
        'id' => 'top_right',
        'name' => $this->l('Top right corner')
      ),
      array(
        'id' => 'bottom_right',
        'name' => $this->l('Bottom right corner')
      ),
      array(
        'id' => 'bottom_left',
        'name' => $this->l('Bottom left corner')
      ),
      array(
        'id' => 'center',
        'name' => $this->l('Center')
      ),
    );

    $this->fields_form = array(
      'tinymce' => true,
      'legend' => array(
        'title' => $this->l('HOME SLIDER'),
        'icon' => 'icon-list-ul'
      ),
      'description' => $desc,
      'input' => array(
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
          'type' => 'file_lang',
          'label' => $this->l('Select a file'),
          'name' => 'image',
          'form_group_class' => 'form_group_img',
          'required' => true,
          'lang' => true,
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Slide title'),
          'name' => 'title',
          'lang' => true,
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Target URL'),
          'name' => 'url',
          'lang' => true,
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Caption'),
          'name' => 'caption',
          'lang' => true,
        ),
        array(
          'type' => 'textarea',
          'label' => $this->l('Description'),
          'name' => 'description',
          'lang' => true,
          'autoload_rte' => true,
          'rows' => 10,
          'cols' => 100,
          'hint' => $this->l('Invalid characters:').' <>;=#{}'
        ),
        array(
          'type' => 'select',
          'label' => $this->l('Position description'),
          'name' => 'position_desc',
          'options' => array(
            'query' =>$position,
            'name' => 'name',
            'id' => 'id'
          )
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Width description'),
          'name' => 'width_desc',
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Min height description'),
          'name' => 'height_desc',
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Opacity background description'),
          'name' => 'opacity_desc',
        ),
      ),
      'submit' => array(
        'title' => $this->l('Save'),
      ),
      'buttons' => array(
        'save-and-stay' => array(
          'title' => $this->l('Save and stay'),
          'name' => 'submitAdd'.$this->table.'AndStay',
          'type' => 'submit',
          'class' => 'btn btn-default pull-right',
          'icon' => 'process-icon-save'
        ),
      ),
    );

    $fields = array();
    $obj = $this->loadObject(true);
    $languages = Language::getLanguages(false);

    foreach ($languages as $lang)
    {
      $image = $this->_imgDir.'slides/'.$obj->id.'_'.$lang['id_lang'].'.'.$this->imageType;
      $image_size = file_exists($image) ? filesize($image) / 1000 : false;
      if($image_size){
        $image_url = $this->_images.'slides/'.$obj->id.'_'.$lang['id_lang'].'.'.$this->imageType;
      }
      else{
        $image_url = false;
      }
      $fields[$lang['id_lang']] = array(
        'url'  => $image_url,
        'size' => $image_size,
      );
    }

    $this->tpl_form_vars['lang_def'] = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
    $this->tpl_form_vars['images'] = $fields;
    $this->tpl_form_vars['save_error'] = !empty($this->errors);
    $this->tpl_form_vars['idLang'] = $this->_idLang;
    $this->tpl_form_vars['idShop'] = $this->_idShop;
    $this->tpl_form_vars['token_slider'] = Tools::getAdminTokenLite('AdminHomeSlider');
    $this->tpl_form_vars['PS_ALLOW_ACCENTED_CHARS_URL'] = (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');

    return parent::renderForm();
  }

  public function displayAjax()
  {
    $json = array();
    try{
      if (Tools::getValue('action') == 'saveGeneralSettings'){
        $settings = Tools::unserialize(Configuration::get('GOMAKOIL_HOME_SLIDER'));

        $fields = array(
          'active'              => Tools::getValue('active'),
          'width'               => Tools::getValue('width'),
          'hook'                => Tools::getValue('hook'),
          'height'              => Tools::getValue('height'),
          'auto_play'           => Tools::getValue('auto_play'),
          'speed'               => Tools::getValue('speed'),
        );
        $error = $this->validBaseFields($fields);
        if($error){
          throw new Exception( $error );
        }
        $base = serialize($fields);
        Configuration::updateValue('GOMAKOIL_HOME_SLIDER', $base);

        if(($settings['width'] !== $fields['width']) || $settings['height'] !== $fields['height']){
          $this->_homeSlider->resizeSliderImages();
        }

        $desc = ' ';
   

        $json['success'] = Module::getInstanceByName('mpm_homeslider')->l("Successfully saved!") ;
        $json['desc'] = $desc;
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

  public function validBaseFields($fields){
    $error = '';
    $n = 0;
    if(!$fields['width'] || !Validate::isInt($fields['width'])){
      $n += 1;
      $error .= '<p>'.$n.'. '.Module::getInstanceByName('mpm_homeslider')->l("Invalid values, 'Width slider' !").'</p>';
    }
    if(!$fields['height'] || !Validate::isInt($fields['height'])){
      $n += 1;
      $error .= '<p>'.$n.'. '.Module::getInstanceByName('mpm_homeslider')->l("Invalid values, 'Height slider' !").'</p>';
    }
    if(!$fields['speed'] || !Validate::isInt($fields['speed'])){
      $n += 1;
      $error .= '<p>'.$n.'. '.Module::getInstanceByName('mpm_homeslider')->l("Invalid values, 'Speed' !").'</p>';
    }
    if(!Validate::isInt($fields['auto_play'])){
      $n += 1;
      $error .= '<p>'.$n.'. '.Module::getInstanceByName('mpm_homeslider')->l("Invalid values, 'Auto play' !").'</p>';
    }
    return $error;
  }

  protected function postImage($id)
  {
    $settings = Tools::unserialize(Configuration::get('GOMAKOIL_HOME_SLIDER'));
    $width = $settings['width'];
    $height = $settings['height'];
    $res = $this->uploadImage($id, 'image', $this->_imgDir, 'jpg', $width, $height);
    $this->_homeSlider->resizeSliderImages($id);
    return $res;
  }

  protected function uploadImage($id, $name, $dir, $ext = false, $width = null, $height = null)
  {
    $errors = array();
    $slide = new blockHomeSlide($id);

    /* Sets each langue fields */
    $languages = Language::getLanguages(false);
    $lang_def = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
    $image = $this->_imgDir.$id.'_'.($lang_def->id).'.'.$this->imageType;
    $image_size = file_exists($image) ? filesize($image) / 1000 : false;

    foreach ($languages as $language)
    {
      $image_size = file_exists($image) ? filesize($image) / 1000 : false;
      if(!$_FILES['image_'.$language['id_lang']]['size'] && !$image_size){
        $this->errors[] = Tools::displayError('An error occurred while uploading image.');
        return false;
      }

      if(!$_FILES['image_'.$language['id_lang']]['tmp_name'] && $image_size){
        $im = $this->_imgDir.$id.'_'.$language['id_lang'].'.'.$this->imageType;
        $im_size = file_exists($im) ? filesize($im) / 1000 : false;
        if(!$im_size){
          $slide->image[$language['id_lang']] = $id.'_'.$lang_def->id.'.'.$this->imageType;
          $slide->save();
        }
      }

      if($_FILES['image_'.$language['id_lang']]['tmp_name']){
        /* Uploads image and sets slide */
        $type = Tools::strtolower(Tools::substr(strrchr($_FILES['image_'.$language['id_lang']]['name'], '.'), 1));
        $imagesize = @getimagesize($_FILES['image_'.$language['id_lang']]['tmp_name']);
        if (isset($_FILES['image_'.$language['id_lang']]) &&
          isset($_FILES['image_'.$language['id_lang']]['tmp_name']) &&
          !empty($_FILES['image_'.$language['id_lang']]['tmp_name']) &&
          !empty($imagesize) &&
          in_array(
            Tools::strtolower(Tools::substr(strrchr($imagesize['mime'], '/'), 1)), array(
              'jpg',
              'gif',
              'jpeg',
              'png'
            )
          ) &&
          in_array($type, array('jpg', 'gif', 'jpeg', 'png'))
        )
        {
          $temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');

          if ($error = ImageManager::validateUpload($_FILES['image_'.$language['id_lang']])){
            $errors[] = $error;
          }
          elseif (!$temp_name || !move_uploaded_file($_FILES['image_'.$language['id_lang']]['tmp_name'], $temp_name)){
            return false;
          }
          elseif (!ImageManager::resize($temp_name, $dir.$id.'_'.$language['id_lang'].'.'.$this->imageType, null, null, $type))
          {
          }
          if (isset($temp_name)){
            @unlink($temp_name);
          }
          $slide->image[$language['id_lang']] = $id.'_'.$language['id_lang'].'.'.$this->imageType;
          $slide->save();
        }
      }

    }
    return true;
  }

  public function ajaxProcessUpdatePositions()
  {
    $block_home_slider = Tools::getValue('block_home_slider');
    foreach($block_home_slider as $key => $value){
      $value = explode('_', $value);
      Db::getInstance()->update('block_home_slider', array('position' => (int)$key), 'id_block_home_slider='.(int)$value[2]);
    }
  }

  public function getSliderImage($image){

    $image_s = $this->_imgDir.'slides/'.$image;
    $image_size = file_exists($image_s) ? filesize($image_s) / 1000 : false;

    if($image && $image_size){
      $image_url = '<img src="'.$this->_images.'slides/'.$image.'" class="img-thumbnail" >';
    }
    else{
      $image_url = '<img src="'.$this->_images.'slides/default.jpg" class="img-thumbnail" >';
    }
    return $image_url;
  }

}