<?php
if (!defined('_PS_VERSION_')) { exit; }

class MyHtmlBlock extends Module implements \PrestaShop\PrestaShop\Core\Module\WidgetInterface
{
    public function __construct()
    {
        $this->name = 'myhtmlblock';
        $this->version = '1.0.0';
        $this->author = 'You';
        $this->tab = 'front_office_features';
        $this->need_instance = 0;
        parent::__construct();

        $this->displayName = $this->l('My HTML Block');
        $this->description = $this->l('Displays custom HTML/text in any hook.');
    }

    public function install()
    {
        return parent::install()
            && Configuration::updateValue('MYHTMLBLOCK_CONTENT', '<p><a href="/kontakt">Kontakt </a></p>');
        // Nie rejestrujemy konkretnych hooków na sta³e – przeszczepisz modu³ do dowolnego hooka w Pozycjach.
    }

    public function uninstall()
    {
        return Configuration::deleteByName('MYHTMLBLOCK_CONTENT') && parent::uninstall();
    }

    public function getContent()
    {
        if (Tools::isSubmit('submitMyHtmlBlock')) {
            $content = Tools::getValue('MYHTMLBLOCK_CONTENT', '');
            Configuration::updateValue('MYHTMLBLOCK_CONTENT', $content, true);
            $this->context->controller->confirmations[] = $this->l('Saved.');
        }

        $content = Configuration::get('MYHTMLBLOCK_CONTENT');
        return '
        <form method="post">
            <div class="form-group">
                <label>'.$this->l('HTML content').'</label>
                <textarea name="MYHTMLBLOCK_CONTENT" class="form-control" rows="8">'.htmlspecialchars($content).'</textarea>
            </div>
            <button class="btn btn-primary" name="submitMyHtmlBlock">'.$this->l('Save').'</button>
        </form>';
    }

    /** WidgetInterface **/
    public function renderWidget($hookName, array $params)
    {
        $this->smarty->assign($this->getWidgetVariables($hookName, $params));
        return $this->fetch('module:'.$this->name.'/views/templates/hook/block.tpl');
    }

    public function getWidgetVariables($hookName, array $params)
    {
        return ['content' => Configuration::get('MYHTMLBLOCK_CONTENT')];
    }
}

