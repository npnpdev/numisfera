{extends file="helpers/form/form.tpl"}
{block name="input_row"}
  {if $input.type == 'checkbox_table'}
    {assign var=all_setings value=$input.values}
    {assign var=id value=$all_setings['id']}
    {assign var=name value=$all_setings['name']}
    {if isset($all_setings) && count($all_setings) > 0}
      <div class="form-group {$input.class_block|escape:'htmlall':'UTF-8'}"  {if $input.display}style="display: block" {/if}>
        <label class="control-label col-lg-4">
        <span class="{if $input.hint|escape:'htmlall':'UTF-8'}label-tooltip{else}control-label{/if}" data-toggle="tooltip" data-html="true" title="" data-original-title="{if $input.hint}{$input.hint|escape:'htmlall':'UTF-8'}{/if}">
          {$input.label|escape:'htmlall':'UTF-8'}
        </span>
        </label>
        <div class="col-lg-8">
          <div class="row">
            <div class="">
              {if $input.id_shop|escape:'htmlall':'UTF-8'}<input type="hidden" name="id_shop" value="{$input.id_shop|escape:'htmlall':'UTF-8'}" >{/if}
              {if $input.id_lang|escape:'htmlall':'UTF-8'}<input type="hidden" name="id_lang" value="{$input.id_lang|escape:'htmlall':'UTF-8'}" >{/if}
              <table class="table table-bordered">
                <thead>
                <tr>
                  <th class="fixed-width-xs">
                <span class="title_box">
                  {l s='Check'  mod='mpm_blog'}
                </span>
                  </th>
                  <th>
                    <span class="id-box">
                     {l s='ID'  mod='mpm_blog'}
                    </span>
                  </th>
                  {if $input.search}
                    <th>
                      <a href="#" id="show_checked" class="btn btn-default"><i class="icon-check-sign"></i> {l s='Show Checked'  mod='mpm_blog'}</a>
                      &nbsp;
                      <a href="#" id="show_all" class="btn btn-default"><i class="icon-check-empty"></i> {l s='Show All'  mod='mpm_blog'}</a>
                    </th>
                  {/if}
                  <th>
                    <span class="title_box">
                      {if $input.search}
                        <input type="text" class="search_checkbox_table" placeholder="{l s='search...'  mod='mpm_blog'}">
                      {/if}
                    </span>
                  </th>
                </tr>
                </thead>
                <tbody>
                {foreach $all_setings['query'] as $key => $setings}
                  <tr>
                    <td >
                      <input type="checkbox" class="{$input.type|escape:'htmlall':'UTF-8'} {$input.class_input|escape:'htmlall':'UTF-8'}" name="{$input.name|escape:'htmlall':'UTF-8'}_{$setings[$id]|escape:'htmlall':'UTF-8'}" id="{$input.name|escape:'htmlall':'UTF-8'}_{$setings[$id]|escape:'htmlall':'UTF-8'}" value="{$setings[$id]|escape:'htmlall':'UTF-8'}" {if isset($all_setings['value']) && $all_setings['value'] && in_array($setings[$id], $all_setings['value'])}checked="checked" {/if} />
                    </td>
                    <td width="50px" style="text-align: center">{$setings[$id]|escape:'htmlall':'UTF-8'}</td>
                    <td>
                      <label for="{$input.name|escape:'htmlall':'UTF-8'}_{$setings[$id]|escape:'htmlall':'UTF-8'}">{$setings[$name]|escape:'htmlall':'UTF-8'}{if isset($all_setings['name2']) && $all_setings['name2']} {$setings[$all_setings['name2']]|escape:'htmlall':'UTF-8'}{/if}</label>
                    </td>
                  </tr>
                {/foreach}
                </tbody>
              </table>

            </div>
          </div>
        </div>
      </div>
    {/if}
  {else}
    {$smarty.block.parent}
  {/if}
{/block}
