import pytest, time

from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager

BASE_URL = "https://localhost/"

def _create_chrome_options():
    options = Options()

    options.add_experimental_option("prefs", {
        "autofill.profile_enabled": False,
        "autofill.credit_card_enabled": False,
        "credentials_enable_service": False,
        "profile.password_manager_enabled": False,
    })

    options.set_capability("acceptInsecureCerts", True)
    options.add_argument("--allow-insecure-localhost")
    options.add_argument("--start-maximized")
    options.add_argument("--disable-save-password-bubble")
    options.add_argument("--no-first-run")
    options.add_argument("--no-default-browser-check")
    options.add_argument("--disable-features=AutofillAddressUserConsent,AutofillServerCommunication")

    return options

def _create_webdriver():
    chrome_options = _create_chrome_options()
    service = Service(ChromeDriverManager().install())
    return webdriver.Chrome(service=service, options=chrome_options)

@pytest.fixture(scope="session")
def driver():
    print("[INFO] Start WebDriver")

    browser = _create_webdriver()

    browser.get(BASE_URL)
    yield browser

    # Cleanup po testach
    print("[INFO] Quit WebDriver")
    #time.sleep(9999)
    browser.quit()