{*
* History:
*
* 1.0.0 - First version
*
*  @author    Vincent MASSON <contact@coeos.pro>
*  @copyright Vincent MASSON <www.coeos.pro>
*  @license   http://www.coeos.pro/fr/content/3-conditions-generales-de-ventes

{l s='' mod='openmenu'}
{$|escape:'html':'UTF-8'}
|escape:'html':'UTF-8'
*}

<h1><img src="{$path|escape:'html':'UTF-8'}logo.png"/> {$display_name|escape:'html':'UTF-8'} (v. {$version|escape:'html':'UTF-8'})
 {l s='by' mod='openmenu'} {$author|escape:'html':'UTF-8'}</h1><br/>

 

<ps-panel icon="icon-cogs" header="{l s='Popular addons' mod='openmenu'}">
<fieldset>
<form  method="post" action="" enctype="multipart/form-data" name="monform" class="form-horizontal">

    <ps-tabs position="top" id="menu_module">
            {include file="$tpl_dir./popular_addons.tpl"} 
    </ps-tabs>

    <ps-panel-footer>
        <ps-panel-footer-submit title="{l s='Save' mod='openmenu'}" icon="process-icon-save" direction="right" name="submitconfigadd" value="save"></ps-panel-footer-submit>
    </ps-panel-footer>

</form>
</fieldset>
</ps-panel>
 