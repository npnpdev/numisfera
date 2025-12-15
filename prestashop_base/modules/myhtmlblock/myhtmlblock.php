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
        // Nie rejestrujemy konkretnych hook�w na sta�e � przeszczepisz modu� do dowolnego hooka w Pozycjach.
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
        $content = Configuration::get('MYHTMLBLOCK_CONTENT');

        if (strpos($content, '[LISTA_KATEGORII]') !== false) {
            
            $rootCategory = Category::getRootCategory();
            $mainCategories = $rootCategory->getSubCategories($this->context->language->id);
            
            // Logika zdjęć rezerwowych
            $fallbackImageUrls = [];
            if (!empty($mainCategories) && isset($mainCategories[8])) {
                // Bierzemy wybraną kategorię z listy
                $firstCategoryObj = new Category($mainCategories[8]['id_category'], $this->context->language->id);
                // Pobieramy z niej do 20 produktów, żeby mieć z czego wybierać
                $fallbackProducts = $firstCategoryObj->getProducts($this->context->language->id, 1, 20, 'position', 'asc');
                
                foreach ($fallbackProducts as $fbProduct) {
                    $cover = Product::getCover($fbProduct['id_product']);
                    if ($cover) {
                        $fallbackImageUrls[] = $this->context->link->getImageLink(
                            $fbProduct['link_rewrite'], 
                            $cover['id_image'], 
                            'home_default'
                        );
                    }
                }
                // Mieszamy tablicę, żeby zdjęcia były losowe
                shuffle($fallbackImageUrls);
            }
            // --- KONIEC LOGIKI ZDJĘĆ REZERWOWYCH ---

            $kategorieHtml = '<ul class="blok-kategorii-grid">';
            
            foreach ($mainCategories as $category) {
                if ($category['active']) {
                    $link = $this->context->link->getCategoryLink($category['id_category']);
                    $name = htmlspecialchars($category['name']);
                    $categoryObj = new Category($category['id_category'], $this->context->language->id);
                    
                    $imageUrl = null;
                    $products = $categoryObj->getProducts($this->context->language->id, 1, 1, 'position', 'asc');
                    if (!empty($products)) {
                        $product = $products[0];
                        $cover = Product::getCover($product['id_product']);
                        if ($cover) {
                            $imageUrl = $this->context->link->getImageLink($product['link_rewrite'], $cover['id_image'], 'home_default');
                        }
                    }
                    
                    // Jeśli nie znaleziono zdjęcia dla tej kategorii - używamy zdjęcie rezerwowe
                    if (!$imageUrl && !empty($fallbackImageUrls)) {
                        $imageUrl = array_pop($fallbackImageUrls); // array_pop bierze i usuwa ostatni element, co gwarantuje brak powtórzeń
                    }

                    // --- Budowanie HTML ---
                    $kategorieHtml .= '<li>';
                    $kategorieHtml .= '  <a href="' . $link . '" class="kategoria-obrazek-link">';
                    $kategorieHtml .= '    <div class="kategoria-obrazek">';
                    if ($imageUrl) {
                        $kategorieHtml .= '      <img src="' . $imageUrl . '" alt="' . $name . '">';
                    }
                    $kategorieHtml .= '    </div>';
                    $kategorieHtml .= '  </a>';

                    $subcategories = $categoryObj->getSubCategories($this->context->language->id);
                    
                    $kategorieHtml .= '<div class="kategoria-nazwa">';
                    if (!empty($subcategories)) {
                        // WERSJA Z PODKATEGORIAMI: Nazwa jest linkiem, strzałka jest osobnym elementem
                        $kategorieHtml .= '  <a href="' . $link . '">' . $name . '</a>';
                        $kategorieHtml .= '  <span class="strzalka-toggle">&rsaquo;</span>';
                        
                        $subcategoriesHtml = '<ul class="podkategorie-lista">';
                        $displayedSubcats = array_slice($subcategories, 0, 4);
                        foreach ($displayedSubcats as $subcat) {
                            $subcatLink = $this->context->link->getCategoryLink($subcat['id_category']);
                            $subcatName = htmlspecialchars($subcat['name']);
                            $subcategoriesHtml .= '<li><a href="' . $subcatLink . '">' . $subcatName . '</a></li>';
                        }
                        if (count($subcategories) > 4) {
                            $subcategoriesHtml .= '<li><a href="' . $link . '" class="pozostale"> + więcej </a></li>';
                        }
                        $subcategoriesHtml .= '</ul>';
                        $kategorieHtml .= $subcategoriesHtml;
                        
                    } else {
                        // WERSJA BEZ PODKATEGORII: Nazwa jest prostym linkiem, bez strzałki
                        $kategorieHtml .= '  <a href="' . $link . '">' . $name . '</a>';
                    }
                    $kategorieHtml .= '</div>';
                    $kategorieHtml .= '</li>';
                }
            }
            $kategorieHtml .= '</ul>';

            $content = str_replace('[LISTA_KATEGORII]', $kategorieHtml, $content);
        }

        return ['content' => $content];
    }
}

