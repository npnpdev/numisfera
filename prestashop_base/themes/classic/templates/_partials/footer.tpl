{* 
  To jest nasz BLOK GŁÓWNY. 
  On jest kontenerem na wszystko.
*}
<div class="blok-glowny-stopki">

  {* 
    To jest BLOK 1.
    Nowy, pusty blok, który na razie ma tylko styl.
  *}
  <div class="blok-1-stopki">
    <div class="usps-container">
      
      <!-- Gwarancja Satysfakcji -->
      <div class="usp-item">
        <div class="usp-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 19.88a2.89 2.89 0 0 0 4.1 0l1.18-1.18a2 2 0 0 0 0-2.82l-5-5a2 2 0 0 0-2.82 0l-5 5a2 2 0 0 0 0 2.82l1.18 1.18a2.89 2.89 0 0 0 4.1 0z"></path><path d="M12 7.5V13"></path><path d="M12 21.88V19"></path><path d="m7 17-1.5-1.5"></path><path d="M17 17l1.5-1.5"></path></svg>
        </div>
        <div class="usp-text">
          Gwarancja<br>Satysfakcji
        </div>
      </div>

      <!-- Rzetelny opis -->
      <div class="usp-item">
        <div class="usp-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"></path><path d="M9 18h6"></path><path d="M10 22h4"></path></svg>
        </div>
        <div class="usp-text">
          Rzetelny opis<br>stanu zachowania
        </div>
      </div>

      <!-- Dostawa -->
      <div class="usp-item">
        <div class="usp-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M5 18H3c-.6 0-1-.4-1-1V7c0-.6.4-1 1-1h10c.6 0 1 .4 1 1v11"></path><path d="M14 9h4l4 4v4h-8v-4h-2Z"></path><circle cx="7.5" cy="17.5" r="2.5"></circle><circle cx="17.5" cy="17.5" r="2.5"></circle></svg>
        </div>
        <div class="usp-text">
          Katowice<br>Warszawska 26
        </div>
      </div>

      <!-- Terminowa Realizacja -->
      <div class="usp-item">
        <div class="usp-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 12a9.5 9.5 0 1 1-7.1-9.1"></path><path d="M12 8v4l2 2"></path><path d="m18 5 3-3 -3 3"></path></svg>
        </div>
        <div class="usp-text">
          Terminowa<br>Realizacja
        </div>
      </div>

    </div>
  </div>

  {* 
    To jest BLOK 2 - oryginalna stopka
  *}
  <div class="blok-2-stopki">
    <div class="container" id="newsletter_footer">
      <div class="row">
        {block name='hook_footer_before'}
          {hook h='displayFooterBefore'}
        {/block}
      </div>
    </div>
    <div class="footer-container" id ="footer_custom_links">
      <div class="container">
        <div class="row">
          {block name='hook_footer'}
            {hook h='displayFooter'}
          {/block}
        </div>
      </div>
    </div>
  </div>
  {* ===== POCZĄTEK SEKCJI Z LOGOTYPAMI PARTNERÓW ===== *}
  <div class="partner-logos-container">
    <div class="logo-item">
      <a href="https://goo.gl/maps/2KMQ2abkV4C2" target="_blank" rel="noopener">
        <img src="https://numizmatyczny.com/public/assets/images/sklep-numizmatyczny-w-google.png" alt="Google Maps">
      </a>
    </div>
    <div class="logo-item">
      <a href="https://www.facebook.com/jspartner/" target="_blank" rel="noopener">
        <img src="https://numizmatyczny.com/public/assets/images/facebook-logo-sklep-numizmatyczny-jspartner.png" alt="Facebook">
      </a>
    </div>
    <div class="logo-item">
      <a href="#KOSZTY_DOSTAWY" target="_blank" rel="noopener">
        <img src="https://numizmatyczny.com/userdata/public/assets//InPost_logotype_2024.png" alt="InPost">
      </a>
    </div>
    <div class="logo-item">
      <a href="#KOSZTY_DOSTAWY" target="_blank" rel="noopener">
        <img src="https://numizmatyczny.com/userdata/public/assets//pocztapolska%20pionowy.png" alt="Poczta Polska">
      </a>
    </div>
    <div class="logo-item">
      <a href="#KOSZTY_DOSTAWY" target="_blank" rel="noopener">
        <img src="https://numizmatyczny.com/public/assets/images/UPS-LOGO.png" alt="UPS">
      </a>
    </div>
    <div class="logo-item">
      <a href="#SPOSOBY_PLATNOSCI" target="_blank" rel="noopener">
        <img src="https://numizmatyczny.com/public/assets/images/p%C5%82atno%C5%9Bci-visa-mastercard-sklep-numizmatyczny.png" alt="Visa & MasterCard">
      </a>
    </div>
  </div>
  {* ===== KONIEC SEKCJI Z LOGOTYPAMI PARTNERÓW ===== *}
  <div class="row" id="footer_copyright">
    <div class="col-md-12 footer_copyright">
      <p class="text-sm-center" id="copyright_text">
        {block name='copyright_link'}
          Sklep internetowy <a id="shoper_link" href="https://www.shoper.pl/" id="shoper_link">Shoper.pl</a> <br>
          Wszelkie Prawa Zastrzeżone - 2025. Sklep Numizmatyczny.Com
        {/block}
      </p>
    </div>
  </div>

</div>