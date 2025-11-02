<?php

  class AdminMpmSolutionsController extends ModuleAdminController
  {
    public function __construct()
    {
      $this->bootstrap = true;
      $this->multishop_context = -1;
      $this->multishop_context_group = true;
      $this->display = 'edit';
      parent::__construct();
    }
    public function renderForm()
    {
      $tpl = $tpl = Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'mpm_solutions/views/templates/hook/import_module.tpl');
      $tpl->assign([

      ]);

      return $tpl->fetch();
    }
  }