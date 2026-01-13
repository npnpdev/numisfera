<tbody>
{foreach $data as $key => $item}
  <tr>
    <td>
      <input type="checkbox" class="checkbox_table {$class|escape:'htmlall':'UTF-8'}" name="{$name|escape:'htmlall':'UTF-8'}_{$item[$id]|escape:'htmlall':'UTF-8'}" id="{$name|escape:'htmlall':'UTF-8'}_{$item[$id]|escape:'htmlall':'UTF-8'}" {if $items_check && in_array($item[$id], $items_check)}checked="checked" {/if} value="{$item[$id]|escape:'htmlall':'UTF-8'}"  />
    </td>
    {if $item[$id]}
      <td width="50px">{$item[$id]|escape:'htmlall':'UTF-8'}</td>
    {/if}
    <td>
      <label for="{$name|escape:'htmlall':'UTF-8'}_{$item[$id]|escape:'htmlall':'UTF-8'}">{$item[$title]|escape:'htmlall':'UTF-8'}</label>
    </td>
  </tr>
{/foreach}
</tbody>