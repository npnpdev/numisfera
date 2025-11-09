{*
* History:
*
* 1.0.0 - POPULAR ADDONS
*
*  @author    Vincent MASSON <contact@coeos.pro>
*  @copyright Vincent MASSON <www.coeos.pro>
*  @license   https://www.coeos.pro/fr/content/3-conditions-generales-de-ventes
*
*}


<p>
{l s='Do not forget to look at our many other modules on ' mod='vatnumbercleaner'}
{if $come_from neq 'addons'}
 : <a target=_blank href="https://www.coeos.pro/{$iso_code|escape:'html':'UTF-8'}/" class="link_coeos">coeos.pro</a> {l s='or on' mod='vatnumbercleaner'} 
{/if}
<a target=_blank href="https://addons.prestashop.com/{$iso_code|escape:'html':'UTF-8'}/2_community-developer?contributor=6370">
addons <img src="../modules/{$name|escape:'htmlall':'UTF-8'}/views/img/superhero.jpg" title="{l s='I am a Superhero !' mod='vatnumbercleaner'}" alt="{l s='I am a Superhero !' mod='vatnumbercleaner'}"/>
</a> 
</p>

{assign var=popular_addons value=[
        [
        'addons' => 4946,
        'coeos' => 86,
        'name'=>{l s='EU VAT and client group' mod='vatnumbercleaner'}, 
        'desc'=>{l s='This module will allow you to force or not the customer to enter their VAT number to be validated by VIES, which changes the group based on his country.' mod='vatnumbercleaner'}
        ],
        [
        'addons' => 6257,
        'coeos' => 104,
        'name'=>{l s='Private Shop' mod='vatnumbercleaner'}, 
        'desc'=>{l s='PRIVATE SALE: You must login to access it. This module allows you to require visitors to log in to access your shop and it also allows you to customize the presentation of the login page.' mod='vatnumbercleaner'}
        ],
        [
        'addons' => 6278,
        'coeos' => 78,
        'name'=>{l s='List of combinations' mod='vatnumbercleaner'}, 
        'desc'=>{l s='This module allows you to add the list of versions for each product, with a direct link to this declension and a button that can add it to the basket.' mod='vatnumbercleaner'}
        ],
        [
        'addons' => 7923,
        'coeos' => 96,
        'name'=>{l s='Billing HT (without VAT) - B2B' mod='vatnumbercleaner'}, 
        'desc'=>{l s='You have professional clients and individuals on your PrestaShop store? This module will allow you to sell and charge for with or without VAT according to group of customer from your customers.' mod='vatnumbercleaner'}
        ],
        [
        'addons' => 27158,
        'coeos' => 122,
        'name'=>{l s='European VAT number' mod='vatnumbercleaner'}, 
        'desc'=>{l s='Enables you to enter the intra-community VAT number when creating / modifying an address, and clean the VAT numbers in the database with a thorough check. Avoid very easily the VAT scams!' mod='vatnumbercleaner'}
        ],
        [
        'addons' => 40987,
        'coeos' => 124,
        'name'=>{l s='Secure your shop!' mod='vatnumbercleaner'}, 
        'desc'=>{l s='Your shop has cost thousands of euros, it is your livelihood, so protect it! avoid any flaws, spam, SQL / XSS injections, backup the database and main files and monitor potential attacks' mod='vatnumbercleaner'}
        ],
        [
        'addons' => 42358,
        'coeos' => 132,
        'name'=>{l s='Delivery Zones And Postal Codes' mod='vatnumbercleaner'}, 
        'desc'=>{l s='This module allows to cut a country into new zones (regions, departments, postal codes ...) in order to assign for each zone a specific carrier (or several) with a specific tariff.' mod='vatnumbercleaner'}
        ],
        [
        'addons' => 30002,
        'coeos' => 131,
        'name'=>{l s='Image And Thumbnail Manager' mod='vatnumbercleaner'}, 
        'desc'=>{l s='Easily manage your product images, categories, manufacturers, suppliers and shops with this manager. It will prevent you from storing unnecessary images, indicates if it is missing images and regenerates images very quickly.' mod='vatnumbercleaner'}
        ],
        [
        'addons' => 27724,
        'coeos' => 129,
        'name'=>{l s='Login To Customer Account Without Password' mod='vatnumbercleaner'}, 
        'desc'=>{l s='This module allows you to connect to the customer account very easily without asking for its password. The connection can be made from the back office, but also from the front office.' mod='vatnumbercleaner'}
        ],
        [
        'addons' => 17320,
        'coeos' => 128,
        'name'=>{l s='Change Of Carrier' mod='vatnumbercleaner'}, 
        'desc'=>{l s='This module allows you to change from the back office the carrier and the cost of delivery. ' mod='vatnumbercleaner'}
        ],
        [
        'addons' => 6291,
        'coeos' => 80,
        'name'=>{l s='SIRET And Customer Group' mod='vatnumbercleaner'}, 
        'desc'=>{l s='Differentiate your business customers of individuals with this SIRET module which transfers the professionals in a given customer group.' mod='vatnumbercleaner'}
        ],
        [
        'addons' => 17994,
        'coeos' => 106,
        'name'=>{l s='EAN Bar Code 8, 13, 15, 18' mod='vatnumbercleaner'}, 
        'desc'=>{l s='Your products and combinations have an EAN? then display the barcode corresponding to this EAN (8, 13, 15 or 18) on your shop and on the invoice and update the stock of these products very easily and very quickly.' mod='vatnumbercleaner'}
        ],
        [
        'addons' => 18077,
        'coeos' => 109,
        'name'=>{l s='Advanced Privilege Card' mod='vatnumbercleaner'}, 
        'desc'=>{l s='With this module you can easily manage assignments client groups of your customers by communicating a valid code at registration or in the "My Account" ' mod='vatnumbercleaner'}
        ],
        [
        'addons' => 18288,
        'coeos' => 111,
        'name'=>{l s='Restriction of modes of payments based on the amount of the cart' mod='vatnumbercleaner'}, 
        'desc'=>{l s='This module allows you to limit the list of payment methods depending on the amount of the customer\'s cart.' mod='vatnumbercleaner'}
        ],
        [
        'addons' => 22531,
        'coeos' => 120,
        'name'=>{l s='Hide prices easily' mod='vatnumbercleaner'}, 
        'desc'=>{l s='This module allows to hide product prices of some categories for certain customer groups but also encourage visitors to register or to connect with personalized messages.' mod='vatnumbercleaner'}
        ]
        ]
    }
<div class="row" id="pop_addons">
{foreach $popular_addons as $infos}
    <div class="col-lg-4 col-md-6 col-sm-12">
    {if $come_from neq 'addons'}
    <a href="https://www.coeos.pro/{$iso_code|escape:'html':'UTF-8'}/index.php?controller=product&id_product={$infos.coeos|escape:'htmlall':'UTF-8'}"
        target="_blank" title="{$infos.name|escape:'htmlall':'UTF-8'}">
    {else}
    <a href="https://addons.prestashop.com/{$iso_code|escape:'htmlall':'UTF-8'}/product.php?id_product={$infos.addons|escape:'htmlall':'UTF-8'}"
        target="_blank" title="{$infos.name|escape:'htmlall':'UTF-8'}">
    {/if}
    <img src="../modules/{$name|escape:'htmlall':'UTF-8'}/views/img/{$infos.coeos|escape:'htmlall':'UTF-8'}.jpg"/>
    <span class="name_addons">{$infos.name|escape:'htmlall':'UTF-8'}</span>
    <p>{$infos.desc|escape:'htmlall':'UTF-8'}</p>
    </a>
    </div>
{/foreach}
</div>

<style type="text/css">
    .name_addons{ font-size:120%;margin-left:10px;font-weight:bold;display:block;height: 67px;}
    #pop_addons img{ width:57px;height:57px;margin: 0 10px 10px 10px;float: left;}
    #pop_addons a{ border:1px solid #d3d8db;margin:5px;padding:10px;display: block;color: #333;text-decoration: none;height:180px}
    a.link_coeos{ font-family: Times New Roman;font-size: 38px;color:#525252;margin-left: 6px;text-shadow: -1px 3px 3px #777, -4px 3px 9px #777;font-style:italic}
    a.link_coeos:hover{ color:#525252;text-decoration: none}
</style>




