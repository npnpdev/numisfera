<?php

require_once (dirname(__FILE__) . '/../../classes/blogComments.php');

class AdminCommentsBlogController extends ModuleAdminController
{
  public function __construct()
	{
		$this->className = 'blogComments';
		$this->table = 'blog_comment';
		$this->bootstrap = true;
		$this->lang = false;
		$this->edit = false;
		$this->delete = true;
		$this->allow_export = true;
		$this->multishop_context = -1;
		$this->multishop_context_group = true;
    $this->position_identifier = 'id_blog_comment';
    $this->translator = Context::getContext()->getTranslator();

    $this->bulk_actions = array(
      'delete' => array(
        'text' => 'Delete selected',
        'icon' => 'icon-trash',
        'confirm' => 'Delete selected items?'
      )
    );

		$this->fields_list = array(
			'id_blog_comment' => array(
        'title' =>  $this->translator->trans('ID', array(), 'Modules.Blog.Admin'),
				'align' => 'center',
        'filter_key' => 'a!id_blog_comment',
				'width' => 20
			),
      'id_blog_post' => array(
        'title' =>  $this->translator->trans('Аrticle', array(), 'Modules.Blog.Admin'),
        'align' => 'center',
        'width' => 100,
        'search' => false,
        'callback' => 'getNamePost',
      ),
			'author_name' => array(
        'title' =>  $this->translator->trans('Customer name', array(), 'Modules.Blog.Admin'),
        'filter_key' => 'a!author_name',
				'width' =>100
			),

      'date_add' => array(
        'title' =>  $this->translator->trans('Creation date', array(), 'Modules.Blog.Admin'),
        'width' =>100
      ),
      'active' => array(
        'title' =>  $this->translator->trans('Status', array(), 'Modules.Blog.Admin'),
        'active' => 'status',
        'align' => 'center',
        'type' => 'bool',
        'width' => 70,
        'orderby' => false
      ),
		);
		parent::__construct();
	}

	public function init()
	{
		parent::init();
	}

	public function postProcess()
	{
		return parent::postProcess();;
	}

	public function initContent()
	{
		parent::initContent();
	}
	public function renderList()
	{

	  
    if (Tools::isSubmit('viewblog_comment'))
    {
      parent::renderList();
      $id_comment = Tools::getValue('id_blog_comment');
      $comment = new blogComments($id_comment);
      $html = '
			<div class="panel">
			<fieldset>
					<div style="margin:20px 0;">
					<label>'.$this->l('Article:').'</label>
					<div>'.$this->getNamePostLong($comment->id_blog_post).'</div>
					</div>
					<div style="margin:20px 0;">
					<label>'.$this->l('Customer name:').'</label>
					<div>'.$comment->author_name.'</div>
					</div>
					<div style="margin:20px 0;">
					<label>'.$this->l('Email:').'</label>
					<div>'.$comment->author_email.'</div>
					</div>
					<div style="margin:20px 0;">
					<label>'.$this->l('Comment:').'</label>
					<div>'.$comment->content.'</div>
					</div>
					<div style="margin:20px 0;">
					<label>'.$this->l('Creation date:').'</label>
					<div>'.$comment->date_add.'</div>
					</div>
					<div style="margin:20px 0;">
					<label>'.$this->l('Rating:').'</label>
					<div>'.$comment->rating.'</div>
					</div>
					</fieldset>
					<div class="panel-footer">
					<a id="desc-cs_blog_category-back" class="btn btn-default" href="'.self::$currentIndex.'&token='.$this->token.'">
						<i class="process-icon-back "></i> <span>Back to list</span>
					</a>
					</div></div>
					';
      return $html;
    }
    else{
      $this->addRowAction('view');
      $this->addRowAction('delete');

      return parent::renderList();
    }
	}

	public function renderView()
	{
		return $this->renderList();
	}


  public static function getNamePost($id_post)
  {
    $post = new blogPost($id_post, Context::getContext()->language->id, Context::getContext()->shop->id);
    $post = $post->name;
    $post = mb_substr(strip_tags(Tools::stripslashes($post)),0,30);
    return $post;
  }

  public static function getNamePostLong($id_post)
  {
    $post = new blogPost($id_post, Context::getContext()->language->id, Context::getContext()->shop->id);
    $post = $post->name;
    return $post;
  }
}