<?php

require_once (dirname(__FILE__) . '/../../classes/blogCategory.php');

class AdminCategoryBlogController extends ModuleAdminController
{
  private $_imgDir;
 public function __construct()
	{
		$this->className = 'blogCategory';
		$this->table = 'blog_category';
		$this->bootstrap = true;
		$this->lang = true;
		$this->edit = true;
		$this->delete = true;
		$this->allow_export = true;
		$this->multishop_context = -1;
		$this->multishop_context_group = true;
    $this->position_identifier = 'id_blog_category';
    $this->_defaultOrderBy = 'a!position';
    $this->orderBy = 'position';
    $this->_imgDir = _PS_IMG_DIR_ . 'blog/category/';
    $this->_imgDirBlog = _PS_IMG_DIR_ . 'blog/';
    $this->translator = Context::getContext()->getTranslator();
    $this->bulk_actions = array(
      'delete' => array(
        'text' => 'Delete selected',
        'icon' => 'icon-trash',
        'confirm' => 'Delete selected items?',
      )
    );

		$this->fields_list = array(
			'id_blog_category' => array(
				'title' =>   $this->translator->trans('ID', array(), 'Modules.Blog.Admin'),
				'align' => 'center',
				'filter_key' => 'b!id_blog_category',
				'width' => 20
			),
			'name' => array(
        'title' =>  $this->translator->trans('Name', array(), 'Modules.Blog.Admin'),
        'filter_key' => 'b!name',
				'width' =>100
			),
      'date_add' => array(
        'title' =>  $this->translator->trans('Creation date', array(), 'Modules.Blog.Admin'),
        'maxlength' => 190,
        'width' =>100
      ),
      'active' => array(
        'title' =>  $this->translator->trans('Displayed', array(), 'Modules.Blog.Admin'),
        'active' => 'status',
        'align' => 'center',
        'type' => 'bool',
        'width' => 70,
        'orderby' => false
      ),
      'position' => array(
        'title' =>  $this->translator->trans('Position', array(), 'Modules.Blog.Admin'),
        'width' => 40,
        'filter_key' => 'a!position',
        'align' => 'center',
        'position' => 'position'
      ),
		);
		parent::__construct();
	}
	public static function getNameCategory($category_parent)
	{
		$cat_parrent = new blogCategory($category_parent);
		return $cat_parrent->name[Context::getContext()->shop->id];
	}
	public function init()
	{
		parent::init();
		if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive())
			$this->_where = ' AND b.`id_shop` = '.(int)Context::getContext()->shop->id;
	}

  public function ajaxProcessUpdatePositions()
  {
    $blog_categories = Tools::getValue('blog_category');

    foreach($blog_categories as $key => $value){
      $value = explode('_', $value);
      Db::getInstance()->update('blog_category', array('position' => (int)$key+1), 'id_blog_category='.(int)$value[2]);
    }
  }

	public function renderForm()
	{

    $obj = $this->loadObject(true);
    $image = $this->_imgDir.date('Y-m',strtotime($obj->date_add)).'/'.$obj->id.'.jpg';
    $image_url = ImageManager::thumbnail($image, $this->table.'_'.(int)$obj->id.'.'.$this->imageType, 350,$this->imageType, true, true);
    $image_size = file_exists($image) ? filesize($image) / 1000 : false;


		$this->fields_form = array(
			'tinymce' => true,
			'legend' => array(
				'title' => $this->l('Category'),
				'icon' => 'icon-tags'
			),
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->l('Name'),
					'name' => 'name',
					'lang' => true,
					'size' => 48,
					'required' => true,
					'class' => 'copy2friendlyUrl',
					'hint' => $this->l('Invalid characters:').' <>;=#{}',
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Displayed'),
					'name' => 'active',
					'required' => false,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					)
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Allow comment'),
					'name' => 'allow_comment',
					'required' => false,
					'values' => array(
						array(
							'id' => 'allow_comment_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'allow_comment_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					)
				),
        array(
          'type' => 'file',
          'label' => $this->l('Image'),
          'name' => 'image',
          'display_image' => true,
          'image' => $image_url ? $image_url : false,
          'size' => $image_size,
          'delete_url' => self::$currentIndex.'&'.$this->identifier.'='.$obj->id.'&token='.$this->token.'&deleteImage=1',
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
					'type' => 'text',
					'label' => $this->l('Meta title'),
					'name' => 'meta_title',
					'lang' => true,
					'hint' => $this->l('Forbidden characters:').' <>;=#{}'
				),
				array(
					'type' => 'text',
					'label' => $this->l('Meta description'),
					'name' => 'meta_description',
					'lang' => true,
					'hint' => $this->l('Forbidden characters:').' <>;=#{}'
				),
				array(
					'type' => 'text',
					'label' => $this->l('Meta keywords'),
					'name' => 'meta_keywords',
					'lang' => true,
					'hint' => $this->l('Forbidden characters:').' <>;=#{}'
				),
				array(
					'type' => 'text',
					'label' => $this->l('Friendly URL'),
					'name' => 'link_rewrite',
          'lang' => true,
					'required' => true,
					'hint' => $this->l('Only letters and the minus (-) character are allowed.')
				),
        array(
          'type' => 'hidden',
          'name' => 'PS_ALLOW_ACCENTED_CHARS_URL',
        ),
			),
			'submit' => array(
				'title' => $this->l('Save')
			),
		);

		$this->fields_value['PS_ALLOW_ACCENTED_CHARS_URL'] = Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL',null,null,Context::getContext()->shop->id);
		return parent::renderForm();
	}

	public function initPageHeaderToolbar(){
		if ($this->display == 'view' || $this->display == 'edit')
		{
			$baseUrl = _PS_BASE_URL_SSL_.__PS_BASE_URI__;
			$cat = new blogCategory( Tools::getValue('id_blog_category'), Context::getContext()->language->id );
			$blogUrl = $baseUrl.'blog/' . $cat->link_rewrite;

			$this->page_header_toolbar_btn['preview'] = array(
				'href' => $blogUrl,
        'desc' => 'Preview',
        'short' => 'Preview',
				'target' => true,
			);
		}
		parent::initPageHeaderToolbar();
	}

  public function postProcess()
  {
    if( Tools::getValue('deleteImage') ){
      if (Validate::isLoadedObject($this->loadObject())){
        $this->_deleteImage();
      }
    }

    if (!$this->redirect_after)
      parent::postProcess();
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

	public function renderView()
	{
		return $this->renderList();
	}


  protected function postImage($id)
  {
    if( !file_exists( $this->_imgDirBlog ) ){
      mkdir($this->_imgDirBlog, 0755);
    }
    $obj = $this->loadObject(true);
    if( !file_exists( $this->_imgDir ) ){
      mkdir($this->_imgDir, 0755);
    }
    if( !file_exists( $this->_imgDir.date('Y-m',strtotime($obj->date_add)) ) ){
      mkdir($this->_imgDir.date('Y-m',strtotime($obj->date_add)), 0755);
    }
    $ret = $this->uploadImage($id, 'image', $this->_imgDir.date('Y-m',strtotime($obj->date_add)).'/');

    return $ret;
  }


  protected function uploadImage($id, $name, $dir, $ext = false, $width = null, $height = null)
  {
    $this->_errors = array();
    if (isset($_FILES[$name]['tmp_name']) && !empty($_FILES[$name]['tmp_name']))
    {

      // Delete old image
      if (Validate::isLoadedObject($object = $this->loadObject()))
        $object->deleteImage();
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
        if (!ImageManager::resize($tmpName, $dir.$id.'.'.$this->imageType, (int)$width, (int)$height, ($ext ? $ext : $this->imageType)))
          $this->_errors[] = Tools::displayError('An error occurred while uploading image.');
        if (count($this->_errors))
          return false;
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

  private function _deleteImage()
  {

    $obj = $this->loadObject(true);

    $image = $this->_imgDir.date('Y-m',strtotime($obj->date_add)).'/'.$obj->id.'.jpg';

    if (file_exists($image)) {
      unlink($image);
    }

  }



}