<div class="button_block button_block_{$id|escape:'htmlall':'UTF-8'}">
    <a data-id="{$id|escape:'htmlall':'UTF-8'}" class="position_column"><i class="material-icons">open_with</i></a>
    <label class="button_block_label">{l s='Column_' mod='mpm_topmenu'}{$id|escape:'htmlall':'UTF-8'}</label>

    <div class="button_item">
        <button data-id="{$id|escape:'htmlall':'UTF-8'}" type="button" class="btn btn-default add_column"><i class="material-icons">add_circle_outline</i><span>{l s='Add new column' mod='mpm_topmenu'}</span></button>
        <button data-id="{$id|escape:'htmlall':'UTF-8'}" type="button" class="btn btn-default save_column"><i class="material-icons">save</i><span>{l s='Save column' mod='mpm_topmenu'}</span></button>
        <button data-id="{$id|escape:'htmlall':'UTF-8'}" type="button" class="btn btn-default open_column"><i class="material-icons">mode_edit</i><span>{l s='Edit column' mod='mpm_topmenu'}</span></button>
        <button data-id="{$id|escape:'htmlall':'UTF-8'}" type="button" class="btn btn-default remove_column"><i class="material-icons">delete</i><span>{l s='Remove column' mod='mpm_topmenu'}</span></button>
    </div>
</div>