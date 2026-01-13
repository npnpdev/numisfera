<div class="footer_soc_button">
   <ul>
      {if isset($facebook) && $facebook}
          <li class="facebook icon-gray">
              <a class="" href="{$facebook|escape:'htmlall':'UTF-8'}"></a>
          </li>
      {/if}
       {if isset($twitter) && $twitter}
           <li class="twitter icon-gray">
               <a class="" href="{$twitter|escape:'htmlall':'UTF-8'}"></a>
           </li>
       {/if}
       {if isset($rss) && $rss}
           <li class="rss icon-gray">
               <a class="" href="{$rss|escape:'htmlall':'UTF-8'}"></a>
           </li>
       {/if}
       {if isset($youtube) && $youtube}
           <li class="youtube icon-gray">
               <a class="" href="{$youtube|escape:'htmlall':'UTF-8'}"></a>
           </li>
       {/if}
       {if isset($google) && $google}
           <li class="googleplus icon-gray">
               <a class="" href="{$google|escape:'htmlall':'UTF-8'}"></a>
           </li>
       {/if}
       {if isset($pinterest) && $pinterest}
           <li class="pinterest icon-gray">
               <a class="" href="{$pinterest|escape:'htmlall':'UTF-8'}"></a>
           </li>
       {/if}
       {if isset($vimeo) && $vimeo}
           <li class="vimeo icon-gray">
            <a class="" href="{$vimeo|escape:'htmlall':'UTF-8'}"></a>
           </li>
       {/if}
       {if isset($instagram) && $instagram}
           <li class="instagram icon-gray">
             <a class="" href="{$instagram|escape:'htmlall':'UTF-8'}"></a>
           </li>
       {/if}

   </ul>
</div>