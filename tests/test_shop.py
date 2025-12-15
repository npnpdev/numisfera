import random, time, re, string
from urllib.parse import urljoin

from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.action_chains import ActionChains
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import StaleElementReferenceException

# GLOBAL
BASE_URL = "https://localhost/"
MENU_ID = "#category-2"

def _random_name(prefix=""):
    letters = string.ascii_letters
    name = ''.join(random.choice(letters) for _ in range(6))
    return prefix + name.capitalize()

def _register_new_account(driver, wait):
    driver.get(BASE_URL)

    # 1) link do "moje-konto" z górnego menu i wejście GET-em
    acc_links = driver.find_elements(By.CSS_SELECTOR, "#_desktop_user_info a[href*='moje-konto']")
    assert acc_links, "Nie znaleziono linku do 'moje-konto' w headerze."
    driver.get(acc_links[0].get_attribute("href"))

    # 2) link "Nie masz konta? Załóż je tutaj" i wejście GET-em
    reg_links = driver.find_elements(By.CSS_SELECTOR, ".no-account a[data-link-action='display-register-form']")
    assert reg_links, "Nie znaleziono linku 'Nie masz konta? Załóż je tutaj'."
    driver.get(reg_links[0].get_attribute("href"))

    # 3) wypełnianie formularza rejestracji
    gender_opts = driver.find_elements(By.CSS_SELECTOR, "input[name='id_gender']")
    if gender_opts:
        try:
            random.choice(gender_opts).click()
        except Exception:
            pass

    # dane testowe
    ts = int(time.time())
    firstname = _random_name("Imie")
    lastname = _random_name("Nazw")
    email = f"qa{ts}@example.com"
    password = "Test12345!"

    _robust_clear_and_type(wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, "#field-firstname"))), firstname)
    _robust_clear_and_type(wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, "#field-lastname"))), lastname)
    _robust_clear_and_type(wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, "#field-email"))), email)
    _robust_clear_and_type(wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, "#field-password"))), password)

    # data urodzenia – opcjonalna
    bday_inputs = driver.find_elements(By.CSS_SELECTOR, "#field-birthday")
    if bday_inputs:
        try:
            _robust_clear_and_type(bday_inputs[0], "1990-01-01")
        except Exception:
            pass

    # wymagane checkboxy
    for sel in ("input[name='customer_privacy']", "input[name='psgdpr']"):
        els = driver.find_elements(By.CSS_SELECTOR, sel)
        if els and not els[0].is_selected():
            driver.execute_script("arguments[0].click();", els[0])

    # klik "Zapisz"
    submit_btn = wait.until(EC.element_to_be_clickable((By.CSS_SELECTOR, "button[data-link-action='save-customer']")))
    submit_btn.click()

    # 4) potwierdzenie sukcesu
    def _registered_ok(d):
        return (
            "/moje-konto" in d.current_url
            or d.find_elements(By.CSS_SELECTOR, "a.logout, a[href*='wyloguj']")
            or d.find_elements(By.CSS_SELECTOR, ".account, .links a[href*='informacje'], #history-link")
        )

    wait.until(_registered_ok)
    return {"email": email, "password": password, "firstname": firstname, "lastname": lastname}

"""Znajdź koszyk w headerze (#_desktop_cart) i wejdź do niego GET-em."""
def _open_cart_via_header(driver, wait):
    driver.get(BASE_URL)
    link = wait.until(EC.presence_of_element_located(
        (By.CSS_SELECTOR, "#_desktop_cart .header a[href*='/koszyk']")))
    driver.get(link.get_attribute("href"))
    # potwierdź, że to strona koszyka
    wait.until(lambda d: "/koszyk" in d.current_url)

"""Na stronie koszyka znajdź 'Przejdź do realizacji zamówienia' i wejdź GET-em."""
def _go_to_checkout_from_cart(driver, wait):
    a = wait.until(EC.presence_of_element_located((
        By.CSS_SELECTOR,
        ".checkout.cart-detailed-actions a.btn.btn-primary[href*='/zam%C3%B3wienie'], \
         .checkout.cart-detailed-actions a.btn.btn-primary[href*='/zamówienie']"
    )))
    driver.get(a.get_attribute("href"))
    # jesteśmy na /zamówienie (krok adres)
    wait.until(lambda d: "/zam" in d.current_url)  # akcenty w URL bywają kodowane

"""
Wypełnia formularz adresu w checkout i klika 'Dalej'.
Zakłada widoczny <form ... action="/zamówienie?..."> z polami.
"""
def _fill_address_and_continue(driver, wait, data=None):
    
    # domyślne dane (same litery w imieniu/nazwisku)
    if data is None:
        ts = int(time.time())
        data = {
            "alias": f"Adres{ts}",
            "firstname": "Jan",
            "lastname": "Kowalski",
            "address1": "Testowa 1",
            "postcode": "00-950",
            "city": "Warszawa",
            "phone": "600100200",
            "country_value": "14",  # Polska
        }

    # czekamy na kontener z formularzem adresu
    wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, "form[action*='/zam' i]")))

    def _type_if_present(css, value):
        els = driver.find_elements(By.CSS_SELECTOR, css)
        if els:
            _robust_clear_and_type(els[0], value)

    # alias (opcjonalny)
    _type_if_present("#field-alias", data["alias"])

    # imię/nazwisko – jeśli już wstępnie uzupełnione przez konto, nie dotykamy
    if not driver.find_elements(By.CSS_SELECTOR, "#field-firstname[value]"):
        _type_if_present("#field-firstname", data["firstname"])
    if not driver.find_elements(By.CSS_SELECTOR, "#field-lastname[value]"):
        _type_if_present("#field-lastname", data["lastname"])

    # adresy / kod / miasto
    _type_if_present("#field-address1", data["address1"])
    _type_if_present("#field-postcode", data["postcode"])
    _type_if_present("#field-city", data["city"])

    # telefon (opcjonalny)
    _type_if_present("#field-phone", data["phone"])

    # kraj – ustaw Polskę, jeśli nic nie wybrane
    sel_country = driver.find_elements(By.CSS_SELECTOR, "#field-id_country")
    if sel_country:
        try:
            from selenium.webdriver.support.ui import Select
            select = Select(sel_country[0])
            # jeśli pusta / placeholder – ustaw wartość 14 (Polska)
            cur = sel_country[0].get_attribute("value") or ""
            if not cur:
                select.select_by_value(data["country_value"])
        except Exception:
            pass

    # klik "Dalej" (confirm addresses)
    btn = wait.until(EC.element_to_be_clickable((
        By.CSS_SELECTOR,
        "button.continue.btn.btn-primary[name='confirm-addresses'], \
         button[name='confirm-addresses']"
    )))
    btn.click()

    return True

"""
Na stronie /zamówienie (krok dostawy) wybiera przewoźnika i klika 'Dalej'.
Jeśli jakiś jest już checked – nie zmienia go, tylko przechodzi dalej.
"""
def _select_carrier_and_continue(driver, wait, randomize=False):
    # czekamy na listę opcji dostawy
    wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, ".delivery-options, .js-delivery-option")))
    radios = driver.find_elements(By.CSS_SELECTOR, ".js-delivery-option input[type='radio'][name^='delivery_option']")
    assert radios, "Brak przewoźników na kroku dostawy."

    # czy coś jest już zaznaczone?
    checked = [r for r in radios if r.is_selected()]
    if not checked:
        target = random.choice(radios) if randomize else radios[0]
        try:
            driver.execute_script("arguments[0].click();", target)
            # krótki wait aż atrybut checked wejdzie
            WebDriverWait(driver, 3).until(lambda d: target.is_selected())
        except Exception:
            pass

    # klik 'Dalej' (potwierdzenie przewoźnika)
    btn = wait.until(EC.element_to_be_clickable((
        By.CSS_SELECTOR, "button.continue.btn.btn-primary[name='confirmDeliveryOption']"
    )))
    btn.click()

    # sukces = zniknął przycisk confirmDeliveryOption i pojawiły się opcje płatności
    return True

"""Czy przycisk jest klikalny? (bez atrybutu disabled i bez klasy 'disabled')."""
def _is_button_enabled(el):
    disabled_attr = (el.get_attribute("disabled") or "").strip().lower()
    cls = (el.get_attribute("class") or "").lower()
    return (not disabled_attr) and ("disabled" not in cls)

"""Zaznacz WYŁĄCZNIE wymagane zgody na kroku płatności."""
def _tick_required_terms_only(driver, wait):
    terms = driver.find_elements(
        By.CSS_SELECTOR,
        "#conditions-to-approve input[type='checkbox'][required]"
    )
    if terms and not terms[0].is_selected():
        driver.execute_script("arguments[0].click();", terms[0])
        WebDriverWait(driver, 3).until(lambda d: terms[0].is_selected())

"""
Na kroku płatności wybiera metodę 'przy odbiorze'. 
NIE klika 'Złóż zamówienie'. 
Zwraca element przycisku 'Złóż zamówienie'.
"""
def _select_cod_without_submitting(driver, wait):
    # czekamy na listę metod płatności
    wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, ".payment-options")))

    # znajdź radio COD
    radios = driver.find_elements(By.CSS_SELECTOR, ".payment-option input[name='payment-option']")
    assert radios, "Brak opcji płatności."
    cod = None
    for r in radios:
        module = (r.get_attribute("data-module-name") or "").lower()
        if "cashondelivery" in module:
            cod = r
            break
    if cod is None:
        # awaryjnie wyszukaj po etykiecie
        for r in radios:
            try:
                lbl = driver.find_element(By.CSS_SELECTOR, f"label[for='{r.get_attribute('id')}']")
                if "przy odbiorze" in (lbl.text or "").lower() or "gotówką" in (lbl.text or "").lower():
                    cod = r
                    break
            except Exception:
                pass
    assert cod is not None, "Nie znaleziono opcji 'przy odbiorze'."

    # wybierz COD
    driver.execute_script("arguments[0].scrollIntoView({block:'center'});", cod)
    driver.execute_script("arguments[0].click();", cod)
    WebDriverWait(driver, 3).until(lambda d: cod.is_selected())

    # tylko wymagane zgody
    _tick_required_terms_only(driver, wait)

    # pobierz przycisk 'Złóż zamówienie'
    place_btn_sel = "#payment-confirmation button[type='submit']"
    wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, place_btn_sel)))
    btn = driver.find_element(By.CSS_SELECTOR, place_btn_sel)

    # czekamy krótko aż JS odblokuje
    WebDriverWait(driver, 5).until(lambda d: _is_button_enabled(btn))
    return btn

"""Kliknięcie 'Złóż zamówienie' i oczekiwanie na potwierdzenie."""
def _submit_order(driver, wait):
    btn = driver.find_element(By.CSS_SELECTOR, "#payment-confirmation button[type='submit']")
    driver.execute_script("arguments[0].scrollIntoView({block:'center'});", btn)
    driver.execute_script("arguments[0].click();", btn)

    # sukces – strona potwierdzenia / widżet potwierdzenia
    wait.until(lambda d: any([
        "/order-confirmation" in d.current_url.lower(),
        "potwierdzen" in d.current_url.lower(),
        d.find_elements(By.CSS_SELECTOR, ".order-confirmation, #order-confirmation, .card.order-confirmation"),
    ]))
    return True

def _open_account_page(driver, wait):
    driver.get(BASE_URL)
    # próbujemy najpierw bezpośredni link "konto" w headerze
    a = wait.until(EC.presence_of_element_located((
        By.CSS_SELECTOR,
        "#_desktop_user_info a.account[href*='/moje-konto'], #_desktop_user_info a[href*='/moje-konto']"
    )))
    driver.get(a.get_attribute("href"))
    wait.until(lambda d: "/moje-konto" in d.current_url)

def _open_orders_history(driver, wait):
    # link „Historia i szczegóły zamówień”
    a = wait.until(EC.presence_of_element_located((
        By.CSS_SELECTOR, "a#history-link[href*='historia-zamowien'], a#history-link[href*='historia-zamówien']"
    )))
    driver.get(a.get_attribute("href"))
    wait.until(lambda d: "historia" in d.current_url)

"""
Zwraca tekst statusu najnowszego zamówienia.
"""
def _get_latest_order_status_text(driver):
    # desktop: pierwszy wiersz w tabeli
    rows = driver.find_elements(By.CSS_SELECTOR, "table.table tbody tr")
    if rows:
        # wiersz ma <span class="label ...">STATUS</span>
        label = rows[0].find_elements(By.CSS_SELECTOR, "span.label")
        if label:
            return (label[0].text or "").strip()

    # mobile
    mob = driver.find_elements(By.CSS_SELECTOR, ".orders .order .status span.label")
    if mob:
        return (mob[0].text or "").strip()

    return ""

"""
Szuka linku do faktury VAT najnowszego zamówienia.
Zwraca href lub None.
"""
def _get_latest_invoice_link(driver):
    # desktop: kolumna „Faktura” zawiera link do pdf-invoice
    a = driver.find_elements(By.CSS_SELECTOR, "table.table tbody tr:first-child td a[href*='controller=pdf-invoice']")
    if a:
        return a[0].get_attribute("href")

    # globalnie (mobile): szukaj linku z pdf-invoice na stronie
    a = driver.find_elements(By.CSS_SELECTOR, "a[href*='controller=pdf-invoice']")
    if a:
        return a[0].get_attribute("href")

    return None


def _get_available_info(driver):
    try:
        span = driver.find_element(By.CSS_SELECTOR, ".product-quantities [data-stock]")
        quantity = int(span.get_attribute("data-stock") or 0)
        out_of_stock_purchasing = int(span.get_attribute("data-allow-oosp") or 0)
        return {"qty": quantity, "allow_oosp": out_of_stock_purchasing}
    except Exception:
        return {"qty": 0, "allow_oosp": 0}

"""Próbuje odczytać licznik sztuk w koszyku."""
def _get_cart_count(driver):
    for sel in (".cart-products-count", ".ajax_cart_quantity",
                "#_desktop_cart .cart-products-count", ".shopping-cart .hidden-sm-down"):
        els = driver.find_elements(By.CSS_SELECTOR, sel)
        if els:
            txt = els[0].text or ""
            m = re.search(r"\d+", txt)
            if m:
                return int(m.group(0))
    return None

def _robust_clear_and_type(el, text):
    el.click()
    el.send_keys(Keys.CONTROL, "a")
    el.send_keys(Keys.BACK_SPACE)
    el.send_keys(str(text))

"""Odpal eventy, żeby JS w PrestaShop przeliczył stan/przycisk."""
def _dispatch_input_change(driver, el):
    driver.execute_script("""
        const el = arguments[0];
        el.dispatchEvent(new Event('input',  {bubbles:true}));
        el.dispatchEvent(new Event('change', {bubbles:true}));
    """, el)

"""Czeka aż zniknie atrybut disabled na przycisku."""
def _wait_button_enabled(wait, driver, btn_sel):
    wait.until(
        lambda d: (lambda b: b is not None and not (b.get_attribute("disabled") or "").strip())
        (d.find_element(By.CSS_SELECTOR, btn_sel))
    )

def _get_top_level_category_hrefs(driver, wait):
    container = wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, MENU_ID)))

    # jeśli menu rozwija się po hoverze – otwórz
    try:
        ActionChains(driver).move_to_element(container).perform()
    except Exception:
        pass

    # linki tylko z tego poziomu (a mają @data-depth='1' i normalny href)
    anchors = container.find_elements(
        By.XPATH,
        ".//li//a[@href and not(contains(@href,'#')) and @data-depth='1']"
    )

    hrefs = [a.get_attribute("href") for a in anchors if a.is_displayed()]

    return hrefs[1:-1]

"""
Ustawia ilość (1..max_try) przyciętą do stanu i klika 'Dodaj do koszyka'.
Zwraca True gdy potwierdzono dodanie (modal lub wzrost licznika).
"""
def _add_to_cart_with_stock(driver, wait, max_try=3):
    info = _get_available_info(driver)
    stock = info["qty"]
    allow_oosp = info["allow_oosp"]

    if stock <= 0 and allow_oosp == 0:
        print("[ERROR] Brak na stanie – pomijam.")
        return False

    wanted = min(stock if allow_oosp == 0 else max_try, random.randint(1, max_try))
    wanted = max(1, wanted)

    # wprowadź ilość
    try:
        qty_input = wait.until(EC.presence_of_element_located((
            By.CSS_SELECTOR, "input#quantity_wanted, input[name='qty'], input[name='quantity']"
        )))
        _robust_clear_and_type(qty_input, wanted)
        _dispatch_input_change(driver, qty_input)
    except Exception:
        wanted = 1  # brak pola – dodamy 1 szt.

    btn_sel = "button.add-to-cart, button#add-to-cart, button[name='add']"
    try:
        _wait_button_enabled(wait, driver, btn_sel)
    except Exception:
        pass

    pre_count = _get_cart_count(driver)

    try:
        add_btn = wait.until(EC.element_to_be_clickable((By.CSS_SELECTOR, btn_sel)))
        add_btn.click()
    except Exception:
        print("[ERROR] Nie mogę kliknąć 'Dodaj do koszyka'.")
        return False

    # sukces
    success = False
    try:
        WebDriverWait(driver, 2).until(
            EC.presence_of_element_located((By.CSS_SELECTOR, "#blockcart-modal, .modal.show"))
        )
        success = True
        # zamykamy
        try:
            close_btn = WebDriverWait(driver, 2).until(EC.element_to_be_clickable((
                By.CSS_SELECTOR, "#blockcart-modal .close, .modal .close, .cart-content-btn .btn-primary"
            )))
            close_btn.click()
        except Exception:
            pass
    except Exception:
        post_count = _get_cart_count(driver)
        if pre_count is not None and post_count is not None and post_count > pre_count:
            success = True

    if success:
        print(f"[SUCCESS] Dodano {wanted} szt.")
        return True
    else:
        print("[WARNING] Nie potwierdzono dodania (brak modala i brak wzrostu licznika).")
        return False

def _search_and_add_first_available(driver, wait, query="moneta", max_pages=8):
    # przejdź na stronę główną (bezpiecznie)
    driver.get(BASE_URL)

    # 1) znajdź pole wyszukiwarki i wpisz zapytanie
    search_input = wait.until(EC.presence_of_element_located((
        By.CSS_SELECTOR, "#search_widget input[name='s'], form[action*='search'] input[name='s']"
    )))
    _robust_clear_and_type(search_input, query)
    search_input.send_keys(Keys.ENTER)

    # 2) iteruj po wynikach; wejdź po kolei w produkty aż znajdziesz dostępny
    for _page in range(max_pages):
        # poczekaj na listing
        wait.until(lambda d: len(d.find_elements(By.CSS_SELECTOR, ".product-miniature")) > 0)

        product_els = driver.find_elements(By.CSS_SELECTOR, ".product-miniature a.product-thumbnail")
        product_links = []

        # BEZPOŚREDNI FIX NA STALE ELEMENT: jedno get_attribute + try/except
        for el in product_els:
            try:
                href = el.get_attribute("href")
            except StaleElementReferenceException:
                # element się "zestarzał" w trakcie – pomijamy
                continue
            if href:
                product_links.append(urljoin(BASE_URL, href))
        time.sleep(0.1)
        print(f"[INFO] Wyników na stronie: {len(product_links)}")

        for i, href in enumerate(product_links, 1):
            driver.get(href)
            print(f"[INFO] Sprawdzam wynik {i}: {href}")

            info = _get_available_info(driver)
            if info["qty"] <= 0 and info["allow_oosp"] == 0:
                print("[ERROR] Brak na stanie – następny wynik")
                continue

            if _add_to_cart_with_stock(driver, wait, max_try=3):
                print("[SUCCESS] Dodano produkt z wyszukiwarki")
                return True

        # 3) (opcjonalnie) paginacja wyników – jeśli chcesz próbować kolejną stronę
        next_btns = driver.find_elements(By.CSS_SELECTOR, ".pagination .next, a[rel='next']")
        if _page + 1 < max_pages and next_btns and next_btns[0].is_enabled():
            try:
                next_btns[0].click()
                time.sleep(0.3)
                continue
            except Exception:
                pass
        break

    print("[WARNING] Nie znaleziono dostępnego produktu dla zapytania:", query)
    return False

def _count_remove_links(driver):
    return len(driver.find_elements(
        By.CSS_SELECTOR, ".remove-from-cart, [data-link-action='delete-from-cart']"
    ))

"""Otwiera stronę koszyka (klik w header lub bezpośredni BASE_URL)."""
def _open_cart(driver, wait):
    # spróbuj kliknąć link w nagłówku
    for sel in ("#_desktop_cart a[href*='koszyk']",
                "a[aria-label*='Koszyk']",
                "a[href*='/koszyk']"):
        links = driver.find_elements(By.CSS_SELECTOR, sel)
        if links:
            try:
                links[0].click()
                # poczekaj, aż pojawią się wiersze koszyka lub kontener koszyka
                wait.until(lambda d: len(d.find_elements(
                    By.CSS_SELECTOR,
                    ".cart-overview, .cart-items, .remove-from-cart, [data-link-action='delete-from-cart']"
                )) > 0)
                return
            except Exception:
                pass

    # fallback: bezpośredni adres
    driver.get(urljoin(BASE_URL, "koszyk?action=show"))
    wait.until(lambda d: len(d.find_elements(
        By.CSS_SELECTOR,
        ".cart-overview, .cart-items, .remove-from-cart, [data-link-action='delete-from-cart']"
    )) > 0)

"""Usuwa n pierwszych produktów z koszyka, po prostu klikając link 'usuń'."""
def _remove_n_from_cart(driver, wait, n=3):
    removed = 0
    for _ in range(n):
        remove_links = driver.find_elements(By.CSS_SELECTOR, ".remove-from-cart, [data-link-action='delete-from-cart']")
        if len(remove_links) == 0:
            break

        before = len(remove_links)
        target = remove_links[0]

        try:
            driver.execute_script("arguments[0].scrollIntoView({block:'center'});", target)
            time.sleep(0.2)
            target.click()
        except Exception:
            print("[WARNING] Nie udało się kliknąć przycisku usuń")
            continue

        # czekamy aż linki się odświeżą i będzie ich mniej
        try:
            wait.until(lambda d: len(d.find_elements(By.CSS_SELECTOR, ".remove-from-cart, [data-link-action='delete-from-cart']")) < before)
            removed += 1
        except Exception:
            print("[WARNING] Element nie zniknął — możliwe, że już pusty wiersz")

    return removed


# ========================= TESTY =========================

# TEST 1
def test_add_10_products(driver):
    wait = WebDriverWait(driver, 15)

    # 1) główne kategorie (1. poziom menu)
    top_level = _get_top_level_category_hrefs(driver, wait)
    selected_cats = random.sample(top_level, 2)

    total_added = 0

    for cat_i, cat_href in enumerate(selected_cats, 1):
        driver.get(cat_href)
        print(f"[INFO] Kategoria {cat_i}: {cat_href}")

        # 2) poczekaj na listę produktów i zbierz linki (stringi)
        wait.until(lambda d: len(d.find_elements(By.CSS_SELECTOR, ".product-miniature")) > 0)
        product_els = driver.find_elements(By.CSS_SELECTOR, ".product-miniature a.product-thumbnail")
        product_links = [urljoin(BASE_URL, el.get_attribute("href")) for el in product_els if el.get_attribute("href")]
        print(f"[INFO] znaleziono {len(product_links)} produktów w tej kategorii")

        # 3) dobijać do 5 dodanych z tej kategorii (próbujemy kolejne produkty)
        added_here = 0
        for prod_i, href in enumerate(product_links, 1):
            if added_here >= 5:
                break
            driver.get(href)
            print(f"   [INFO] Produkt {prod_i} z kat. {cat_i}: {href}")
            if _add_to_cart_with_stock(driver, wait, max_try=3):
                added_here += 1
                total_added += 1

        print(f"[INFO] Dodano z tej kategorii: {added_here}")

        # powrót na stronę główną przed drugą kategorią (jeśli chcesz dalej klikać z menu)
        if cat_i == 1:
            driver.get(BASE_URL)
            wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, MENU_ID)))

    print(f"[SUCCESS] Zakończono: dodano {total_added} produktów do koszyka ")

    assert total_added >= 10, f"Dodano tylko {total_added}, oczekiwano ≥ 10"

# TEST 2
def test_search_add_product_by_name(driver):
    wait = WebDriverWait(driver, 15)
    ok = _search_and_add_first_available(driver, wait, query="moneta", max_pages=1)
    assert ok, "Nie udało się dodać żadnego produktu z wyszukiwarki dla frazy 'moneta'"

# TEST 3
def test_remove_3_items_from_cart(driver):
    wait = WebDriverWait(driver, 15)

    _open_cart(driver, wait)

    start_links = _count_remove_links(driver)
    assert start_links > 0, "Koszyk jest pusty — nie ma co usuwać."

    to_remove = min(3, start_links)
    removed = _remove_n_from_cart(driver, wait, n=to_remove)

    # po usuwaniu policz ponownie linki 'usuń'
    # krótki wait na ustabilizowanie DOM po ostatnim kliknięciu
    try:
        WebDriverWait(driver, 3).until(
            lambda d: _count_remove_links(d) == start_links - removed
        )
    except Exception:
        pass

    end_links = _count_remove_links(driver)
    time.sleep(0.1)
    print(f"[INFO] Usunięto {removed}/{to_remove}. Linki: {start_links} → {end_links}")

    assert removed == to_remove, f"Oczekiwano usunięcia {to_remove}, a usunięto {removed}"
    assert end_links == start_links - to_remove, \
        f"Liczba linków 'usuń' nie spadła o {to_remove}: było {start_links}, jest {end_links}"



# TEST 4
def test_register_new_account(driver):
    wait = WebDriverWait(driver, 15)
    creds = _register_new_account(driver, wait)
    # miękkie potwierdzenie – jesteśmy na koncie lub mamy link wyloguj
    assert ("/moje-konto" in driver.current_url) or \
           (len(driver.find_elements(By.CSS_SELECTOR, "a.logout, a[href*='wyloguj']")) > 0), \
           "Po rejestracji nie widzę strony konta ani linku 'Wyloguj'."
    time.sleep(0.1)
    print(f"[INFO] Zarejestrowano konto: {creds['email']}")

# TEST 5
def test_checkout_address_step(driver):
    wait = WebDriverWait(driver, 15)
    _open_cart_via_header(driver, wait)
    _go_to_checkout_from_cart(driver, wait)
    ok = _fill_address_and_continue(driver, wait)
    time.sleep(0.1)
    assert ok, "Nie udało się przejść z kroku adresu do kolejnego etapu zamówienia."

# Test 6
def test_checkout_select_carrier(driver):
    wait = WebDriverWait(driver, 15)
    time.sleep(0.1)
    assert _select_carrier_and_continue(driver, wait, randomize=False), "Nie udało się zatwierdzić przewoźnika."

# Test 7
def test_select_cod_without_submitting(driver):
    wait = WebDriverWait(driver, 15)
    btn = _select_cod_without_submitting(driver, wait)
    time.sleep(0.1)
    assert _is_button_enabled(btn), "Przycisk 'Złóż zamówienie' nie odblokował się po wyborze COD i T&C."

# Test 8
def test_checkout_submit_cod(driver):
    wait = WebDriverWait(driver, 15)
    time.sleep(0.1)
    assert _submit_order(driver, wait), "Nie udało się sfinalizować zamówienia."

# Test 9
def test_order_status_visible(driver):
    wait = WebDriverWait(driver, 15)
    _open_account_page(driver, wait)
    _open_orders_history(driver, wait)

    # poczekaj na tabelę lub układ mobilny
    wait.until(lambda d: d.find_elements(By.CSS_SELECTOR, "table.table tbody tr, .orders .order"))
    status = _get_latest_order_status_text(driver)

    time.sleep(0.1)
    assert status, "Nie udało się odczytać statusu zamówienia."
    time.sleep(0.1)
    print(f"[INFO] Status najnowszego zamówienia: {status}")

# Test 10
def test_invoice_pdf_link(driver):
    wait = WebDriverWait(driver, 15)

    time.sleep(0.1)
    href = _get_latest_invoice_link(driver)
    assert href, "Nie znaleziono linku do faktury (pdf-invoice)."
    time.sleep(0.1)
    assert "controller=pdf-invoice" in href, f"Link do faktury wygląda podejrzanie: {href}"

    # Sprawdzenie bez pobierania: użyj fetch z cookies użytkownika (same-origin)
    result = driver.execute_async_script("""
        const url = arguments[0];
        const callback = arguments[1];
        fetch(url, { credentials: 'same-origin' })
          .then(res => res.headers.get('content-type'))
          .then(ct => callback({ ok: true, ct }))
          .catch(err => callback({ ok: false, ct: '' }));
    """, href)

    time.sleep(0.1)
    assert result.get("ok"), "Żądanie do faktury nie powiodło się (fetch)."
    content_type = (result.get("ct") or "").lower()
    time.sleep(0.1)
    assert "pdf" in content_type, f"Content-Type nie wygląda na PDF: {content_type}"
    driver.get(href)
    time.sleep(0.1)
    print(f"[INFO] Link do faktury OK, Content-Type: {content_type}")
