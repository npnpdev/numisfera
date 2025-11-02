<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.08.15
 * Time: 17:40
 */
require_once(dirname(__FILE__) . '/../../classes/ContactFormClass.php');

class AdminContactFormController extends ModuleAdminController
{

  private $_idShop;
  private $_idLang;
  private $_imgDir;
  private $_contactFormClass;
  protected $position_identifier = 'id_contactform';

  public function __construct()
  {
    $this->className = 'ContactFormClass';
    $this->table = 'contactform';
    $this->bootstrap = true;
    $this->lang = true;
    $this->edit = true;
    $this->delete = true;
    parent::__construct();
    $this->multishop_context = -1;
    $this->multishop_context_group = true;
    $this->position_identifier = 'id_contactform';
    $this->_idShop = Context::getContext()->shop->id;
    $this->_idLang = Context::getContext()->language->id;
    $this->_contactFormClass = new ContactFormClass();
    $this->_imgDir = _PS_MODULE_DIR_ . 'mpm_contactform/views/img/';

    $this->bulk_actions = array(
      'delete' => array(
        'text' => $this->l('Delete selected'),
        'icon' => 'icon-trash',
        'confirm' => $this->l('Delete selected items?')
      )
    );

    $this->fields_list = array(
      'id_contactform' => array(
        'title' => $this->l('ID'),
        'search' => true,
        'onclick' => false,
        'filter_key' => 'a!id_contactform',
        'width' => 20
      ),
      'date_add' => array(
        'title' => $this->l('Creation date'),
        'maxlength' => 190,
        'width' =>100,
        'align' => 'left',
      ),
    );
  }

  public function init()
  {
    parent::init();
    if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive() && Tools::getValue('viewcontactform') === false)
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
    if( Tools::getValue('deleteImage') ){
      if (Validate::isLoadedObject($object = $this->loadObject())){
        $this->deleteImage($this->_imgDir, $object->id);
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminContactForm').'&updatecontactform&id_contactform='.$object->id);
      }
    }
    return parent::postProcess();
  }

  public function renderForm()
  {
    $obj = $this->loadObject(true);
    $image = $this->_imgDir.$obj->id.'.png';
    $image_url = ImageManager::thumbnail($image, $this->table.'_'.(int)$obj->id.'.png', 350, 'png', true, true);
    $image_size = file_exists($image) ? filesize($image) / 1000 : false;

    $position = array(
      array(
        'id' => 'left',
        'name' => $this->l('Column 1')
      ),
      array(
        'id' => 'center',
        'name' => $this->l('Column 2')
      ),
      array(
        'id' => 'right',
        'name' => $this->l('Column 3')
      ),

      array(
        'id' => 'bottom',
        'name' => $this->l('Column 4')
      ),
    );

    $this->fields_form = array(
      'tinymce' => true,
      'legend' => array(
        'title' => $this->l('CONFIGURATION'),
        'icon' => 'icon-cogs'
      ),
      'tabs' => array(
        'general_settings' => $this->l('General settings'),
        'description_block' => $this->l('Description block settings'),
        'contact_form_block' => $this->l('Contact form settings'),
        'image_block' => $this->l('Image block settings'),
        'maps_block' => $this->l('Maps block settings'),

      ),
      'input' => array(
        array(
          'type' => 'textarea',
          'label' => $this->l('Send notification for'),
          'name' => 'email',
          'autoload_rte' => false,
          'rows' => 3,
          'cols' => 20,
          'tab' => 'general_settings',
          'required' => true,
          'form_group_class' => 'form_field_width_50',
          'desc' => $this->l('Each email must be separated by a comma'),
        ),
        array(
          'type' => 'color',
          'label' => $this->l('Background color'),
          'tab' => 'general_settings',
          'name' => 'background',
          'hint' => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").')
        ),
        array(
          'type' => 'color',
          'label' => $this->l('Font color'),
          'tab' => 'general_settings',
          'name' => 'color',
          'hint' => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").')
        ),
        array(
          'type' => 'html',
          'name' => $this->l(''),
          'form_group_class' => 'form_settings',
          'tab' => 'general_settings',
          'html_content' => $this->l('Button and icons settings')
        ),
        array(
          'type' => 'color',
          'label' => $this->l('Background color'),
          'name' => 'background_button',
          'tab' => 'general_settings',
          'hint' => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").')
        ),
        array(
          'type' => 'color',
          'label' => $this->l('Background color on hover'),
          'name' => 'background_button_hover',
          'tab' => 'general_settings',
          'hint' => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").')
        ),
        array(
          'type' => 'color',
          'label' => $this->l('Font color'),
          'name' => 'color_button',
          'tab' => 'general_settings',
          'hint' => $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").')
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Active'),
          'name' => 'block_description',
          'is_bool' => true,
          'tab' => 'description_block',
          'values' => array(
            array(
              'id' => 'block_description_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'block_description_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Description block width'),
          'name' => 'width_description',
          'tab' => 'description_block',
          'class' => 'form_field_width_200',
          'form_group_class' => 'r',
          'desc' => $this->l('The width should be in percentage (%)'),
        ),
        array(
          'type' => 'select',
          'label' => $this->l('Description block position'),
          'name' => 'position_description',
          'tab' => 'description_block',
          'options' => array(
            'query' => $position,
            'name' => 'name',
            'id' => 'id'
          ),
          'desc' => $this->l('Column position'),
        ),
         array(
          'type' => 'text',
          'label' => $this->l('Title'),
           'tab' => 'description_block',
          'name' => 'title_block_description',
          'form_group_class' => 'r',
          'lang' => true,
        ),
        array(
          'type' => 'textarea',
          'label' => $this->l('Description'),
          'name' => 'description',
          'tab' => 'description_block',
          'lang' => true,
          'autoload_rte' => true,
          'rows' => 10,
          'cols' => 100,
          'hint' => $this->l('Invalid characters:').' <>;=#{}'
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('Active'),
          'name' => 'block_form',
          'is_bool' => true,
          'tab' => 'contact_form_block',
          'values' => array(
            array(
              'id' => 'block_form_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'block_form_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Contact form width'),
          'name' => 'width_form',
          'class' => 'form_field_width_200',
          'tab' => 'contact_form_block',
          'form_group_class' => 'r',
          'desc' => $this->l('The width should be in percentage (%)'),
        ),
        array(
          'type' => 'select',
          'label' => $this->l('Contact form position'),
          'name' => 'position_form',
          'tab' => 'contact_form_block',
          'options' => array(
            'query' => $position,
            'name' => 'name',
            'id' => 'id'
          ),
          'desc' => $this->l('Column position'),
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Title'),
          'name' => 'title_block_form',
          'tab' => 'contact_form_block',
          'form_group_class' => 'r',
          'lang' => true,
        ),
        array(
          'type' => 'html',
          'name' => $this->l('Fields settings'),
          'form_group_class' => 'form_settings',
          'tab' => 'contact_form_block',
          'html_content' => $this->l('Fields settings')
        ),
        array(
          'type' => 'html',
          'name' => $this->l(''),
          'tab' => 'contact_form_block',
          'form_group_class' => 'show_fields_form',
          'html_content' => $this->l('Name')
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('show'),
          'name' => 'name_field',
          'is_bool' => true,
          'tab' => 'contact_form_block',
          'values' => array(
            array(
              'id' => 'name_field_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'name_field_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('required'),
          'name' => 'name_field_required',
          'is_bool' => true,
          'tab' => 'contact_form_block',
          'values' => array(
            array(
              'id' => 'name_field_required_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'name_field_required_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'html',
          'name' => $this->l(''),
          'tab' => 'contact_form_block',
          'form_group_class' => 'show_fields_form',
          'html_content' => $this->l('Email address')
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('show'),
          'name' => 'email_field',
          'tab' => 'contact_form_block',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'email_field_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'email_field_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('required'),
          'name' => 'email_field_required',
          'is_bool' => true,
          'tab' => 'contact_form_block',
          'values' => array(
            array(
              'id' => 'email_field_required_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'email_field_required_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'html',
          'name' => $this->l(''),
          'tab' => 'contact_form_block',
          'form_group_class' => 'show_fields_form',
          'html_content' => $this->l('Phone')
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('show'),
          'name' => 'phone_field',
          'tab' => 'contact_form_block',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'phone_field_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'phone_field_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('required'),
          'name' => 'phone_field_required',
          'is_bool' => true,
          'tab' => 'contact_form_block',
          'values' => array(
            array(
              'id' => 'phone_field_required_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'phone_field_required_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'html',
          'name' => $this->l(''),
          'tab' => 'contact_form_block',
          'form_group_class' => 'show_fields_form',
          'html_content' => $this->l('Subject Heading')
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('show'),
          'name' => 'subject_field',
          'tab' => 'contact_form_block',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'subject_field_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'subject_field_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('required'),
          'name' => 'subject_field_required',
          'is_bool' => true,
          'tab' => 'contact_form_block',
          'values' => array(
            array(
              'id' => 'subject_field_required_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'subject_field_required_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'html',
          'name' => '',
          'tab' => 'contact_form_block',
          'form_group_class' => 'show_fields_form',
          'html_content' => $this->l('Attach File')
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('show'),
          'name' => 'attach_field',
          'tab' => 'contact_form_block',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'attach_field_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'attach_field_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'html',
          'name' => '',
          'tab' => 'contact_form_block',
          'form_group_class' => 'show_fields_form',
          'html_content' => $this->l('Captcha')
        ),
        array(
          'type' => 'switch',
          'label' => $this->l('show'),
          'name' => 'captcha_field',
          'is_bool' => true,
          'tab' => 'contact_form_block',
          'values' => array(
            array(
              'id' => 'captcha_field_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'captcha_field_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),

        array(
          'type' => 'switch',
          'label' => $this->l('Active'),
          'name' => 'block_image',
          'tab' => 'image_block',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'block_image_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'block_image_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Image block width'),
          'name' => 'width_image',
          'class' => 'form_field_width_200',
          'tab' => 'image_block',
          'form_group_class' => 'r',
          'desc' => $this->l('The width should be in percentage (%)'),
        ),
        array(
          'type' => 'select',
          'label' => $this->l('Image block position'),
          'name' => 'position_image',
          'tab' => 'image_block',
          'options' => array(
            'query' => $position,
            'name' => 'name',
            'id' => 'id'
          ),
          'desc' => $this->l('Column position'),
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Title'),
          'name' => 'title_block_image',
          'tab' => 'image_block',
          'form_group_class' => 'r',
          'lang' => true,
        ),
        array(
          'type'              => 'file',
          'label'             => $this->l('Image'),
          'form_group_class'  => 'uploadImagesFormGroup',
          'image'             => $image_url ? $image_url : false,
          'name'              => 'image',
          'tab' => 'image_block',
          'size'              => $image_size,
          'delete_url'        => self::$currentIndex.'&'.$this->identifier.'='.(int)$obj->id.'&token='.$this->token.'&updatecontactform&deleteImage=1',
        ),

        array(
          'type' => 'switch',
          'label' => $this->l('Active'),
          'name' => 'block_maps',
          'tab' => 'maps_block',
          'is_bool' => true,
          'values' => array(
            array(
              'id' => 'block_maps_on',
              'value' => 1,
              'label' => $this->l('Yes')),
            array(
              'id' => 'block_maps_off',
              'value' => 0,
              'label' => $this->l('No')),
          ),
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Maps block width'),
          'name' => 'width_maps_block',
          'tab' => 'maps_block',
          'form_group_class' => 'r',
          'class' => 'form_field_width_200',
          'desc' => $this->l('The width should be in percentage (%)'),
        ),
        array(
          'type' => 'select',
          'label' => $this->l('Maps block position'),
          'name' => 'position_maps',
          'tab' => 'maps_block',
          'options' => array(
            'query' => $position,
            'name' => 'name',
            'id' => 'id'
          ),
          'desc' => $this->l('Column position'),
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Title'),
          'name' => 'title_block_maps',
          'tab' => 'maps_block',
          'form_group_class' => 'r',
          'lang' => true,
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Maps width'),
          'name' => 'width_maps',
          'tab' => 'maps_block',
          'class' => 'form_field_width_200',
          'desc' => $this->l('The width should be in pixels (px)'),
        ),
        array(
          'type' => 'text',
          'label' => $this->l('Maps height'),
          'name' => 'height_maps',
          'tab' => 'maps_block',
          'class' => 'form_field_width_200',
          'desc' => $this->l('The height should be in pixels (px)'),
        ),
        array(
          'type' => 'textarea',
          'label' => $this->l('Maps code'),
          'name' => 'maps_code',
          'tab' => 'maps_block',
          'autoload_rte' => false,
          'rows' => 5,
          'cols' => 20,
          'form_group_class' => 'form_field_width_50',
          'desc'       => htmlentities('<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d167998.10803373056!2d2.2074740643680624!3d48.85877410312378!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e1f06e2b70f%3A0x40b82c3688c9460!2z0J_QsNGA0LjQtiwg0KTRgNCw0L3RhtGW0Y8!5e0!3m2!1suk!2sua!4v1455005606039" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>'),
        ),
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

    $this->tpl_form_vars['idLang'] = $this->_idLang;
    $this->tpl_form_vars['idShop'] = $this->_idShop;
    $this->tpl_form_vars['PS_ALLOW_ACCENTED_CHARS_URL'] = (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');
    return parent::renderForm();
  }

  protected function postImage($id)
  {
    return $this->uploadImage($id, 'image', $this->_imgDir, 'png');
  }

  protected function uploadImage($id, $name, $dir, $ext = false, $width = null, $height = null)
  {
    if (isset($_FILES[$name]['tmp_name']) && !empty($_FILES[$name]['tmp_name']))
    {
      // Delete old image
      if (Validate::isLoadedObject($object = $this->loadObject()))
        $this->deleteImage($this->_imgDir, $object->id);
      else
        return false;

      // Check image validity
      $max_size = isset($this->maxImageSize) ? $this->maxImageSize : 0;
      if ($error = ImageManager::validateUpload($_FILES[$name], Tools::getMaxUploadSize($max_size)))
        $this->_errors[] = $error;
      elseif (!($tmpName = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES[$name]['tmp_name'], $tmpName))
        return false;
      else
      {
        $_FILES[$name]['tmp_name'] = $tmpName;
        // Copy new image
        if (!ImageManagerCore::resize($tmpName, $dir.$id.'.'.($ext ? $ext : $this->imageType), (int)$width, (int)$height, ($ext ? $ext : $this->imageType)))
          $this->_errors[] = Tools::displayError('An error occurred while uploading image.');

        if ($this->afterImageUpload())
        {
          unlink($tmpName);
          return true;
        }
        return false;
      }
    }
    return true;
  }

  protected function deleteImage($image, $id)
  {
    $file_name = $image.$id.'.png';
    if (realpath(dirname($file_name)) != realpath($image))
      Tools::dieOrLog(sprintf('Could not find upload directory'));

    if ($image != '' && is_file($file_name)){
      unlink($file_name);
    }
  }

}