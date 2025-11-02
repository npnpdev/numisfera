{if isset($msg) && $msg}
    <div class="alert alert-info" role="alert">
        <i class="material-icons">help</i>
        <p class="alert-text">{$msg|escape:'htmlall':'UTF-8'}</p>
    </div>
{/if}