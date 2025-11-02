<div style="width: 50%; min-width: 160px;margin: 0 auto;margin-top: 40px;margin-bottom: 40px;border: 1px solid #dadada;border-radius: 6px;    ">


    <div style="padding: 20px;border-bottom: 1px solid #dadada;font-size: 20px;text-align: center;
        border-radius: 6px 6px 0px 0px;
        background-image: -ms-linear-gradient(top, #FFFFFF 0%, #FFFFFF 20%, #FCFCFC 40%, #FAFAFA 60%, #FAFAFA 80%, #EDEDED 100%);
        background-image: -moz-linear-gradient(top, #FFFFFF 0%, #FFFFFF 20%, #FCFCFC 40%, #FAFAFA 60%, #FAFAFA 80%, #EDEDED 100%);
        background-image: -o-linear-gradient(top, #FFFFFF 0%, #FFFFFF 20%, #FCFCFC 40%, #FAFAFA 60%, #FAFAFA 80%, #EDEDED 100%);
        background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #FFFFFF), color-stop(20, #FFFFFF), color-stop(40, #FCFCFC), color-stop(60, #FAFAFA), color-stop(80, #FAFAFA), color-stop(100, #EDEDED));
        background-image: -webkit-linear-gradient(top, #FFFFFF 0%, #FFFFFF 20%, #FCFCFC 40%, #FAFAFA 60%, #FAFAFA 80%, #EDEDED 100%);
        background-image: linear-gradient(to bottom, #FFFFFF 0%, #FFFFFF 20%, #FCFCFC 40%, #FAFAFA 60%, #FAFAFA 80%, #EDEDED 100%);">
        {l s='New comment'  mod='mpm_blog'}
    </div>

    <div style="padding: 30px;font-size: 14px;    background-color: #fbfafa;">

        {if isset($post) && $post}
            <div style="margin-bottom: 10px;">
                <div style="float: left;width: 150px;margin: 2px 10px 2px 0px;">
                    <strong>{l s='Article:'  mod='mpm_blog'}</strong>
                </div>
                <div style="float: left;margin-top: 2px;width: 65%;">
                    <a href="{$url|escape:'htmlall':'UTF-8'}">{$post|escape:'htmlall':'UTF-8'}</a>
                </div>
                <div style="clear: both;"></div>
            </div>
        {/if}


        {if isset($comment) && $comment}
            <div style="margin-bottom: 10px;">
                <div style="float: left;width: 150px;margin: 2px 10px 2px 0px;">
                    <strong>{l s='Comment:'  mod='mpm_blog'}</strong>
                </div>
                <div style="float: left;margin-top: 2px;">{$comment|escape:'htmlall':'UTF-8'}</div>
                <div style="clear: both;"></div>
            </div>
        {/if}


        {if isset($raty) && $raty}
           <div style="margin-bottom: 10px;">
               <div style="float: left;width: 150px;margin: 2px 10px 2px 0px;">
                   <strong>{l s='Rating:'  mod='mpm_blog'}</strong>
               </div>
               <div style="float: left;margin-top: 2px;">{$raty|escape:'htmlall':'UTF-8'}</div>
               <div style="clear: both;"></div>
           </div>
        {/if}


        {if isset($name) && $name}
            <div style="margin-bottom: 10px;">
                <div style="float: left;width: 150px;margin: 2px 10px 2px 0px;">
                    <strong>{l s='Customer name:'  mod='mpm_blog'}</strong>
                </div>
                <div style="float: left;margin-top: 2px;">{$name|escape:'htmlall':'UTF-8'}</div>
                <div style="clear: both;"></div>
            </div>
        {/if}

        {if isset($email) && $email}
            <div style="margin-bottom: 10px;">
                <div style="float: left;width: 150px;margin: 2px 10px 2px 0px;">
                    <strong>{l s='Customer email:'  mod='mpm_blog'}</strong>
                </div>
                <div style="float: left;margin-top: 2px;">{$email|escape:'htmlall':'UTF-8'}</div>
                <div style="clear: both;"></div>
            </div>
        {/if}

        <div style="clear: both;display: block !important;"></div>


    </div>


</div>