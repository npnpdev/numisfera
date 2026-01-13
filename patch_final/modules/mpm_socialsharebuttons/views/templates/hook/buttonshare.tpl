

<ul class="rrssb-buttons rrssb-1"  data-image="{$image['bySize']['small_default']['url']|escape:'htmlall':'UTF-8'}" data-url="{$url|escape:'htmlall':'UTF-8'}" data-title="{$name|escape:'htmlall':'UTF-8'}" data-description="{strip_tags($description)|truncate:150:'...'|escape:'htmlall':'UTF-8'}" data-emailBody="{$email|escape:'htmlall':'UTF-8'}">
    {if isset($buttons['button_facebook']) && $buttons['button_facebook']}
        <li class="rrssb-facebook" data-initwidth="6.25" data-size="68" style="width: calc(7.69231% - 9.69231px);">
            <a href="" class="popup">
                  <span class="rrssb-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29 29">
                        <path d="M26.4 0H2.6C1.714 0 0 1.715 0 2.6v23.8c0 .884 1.715 2.6 2.6 2.6h12.393V17.988h-3.996v-3.98h3.997v-3.062c0-3.746 2.835-5.97 6.177-5.97 1.6 0 2.444.173 2.845.226v3.792H21.18c-1.817 0-2.156.9-2.156 2.168v2.847h5.045l-.66 3.978h-4.386V29H26.4c.884 0 2.6-1.716 2.6-2.6V2.6c0-.885-1.716-2.6-2.6-2.6z"></path>
                      </svg>
                  </span>
                <span class="rrssb-text">{l s='facebook'  mod='mpm_socialsharebuttons'}</span>
            </a>
        </li>
    {/if}

    {if isset($buttons['button_twitter']) && $buttons['button_twitter']}
        <li class="rrssb-twitter" data-initwidth="6.25" data-size="52" style="width: calc(7.69231% - 9.69231px);">
            <a href="" class="popup">
                  <span class="rrssb-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28">
                        <path d="M24.253 8.756C24.69 17.08 18.297 24.182 9.97 24.62c-3.122.162-6.22-.646-8.86-2.32 2.702.18 5.375-.648 7.507-2.32-2.072-.248-3.818-1.662-4.49-3.64.802.13 1.62.077 2.4-.154-2.482-.466-4.312-2.586-4.412-5.11.688.276 1.426.408 2.168.387-2.135-1.65-2.73-4.62-1.394-6.965C5.574 7.816 9.54 9.84 13.802 10.07c-.842-2.738.694-5.64 3.434-6.48 2.018-.624 4.212.043 5.546 1.682 1.186-.213 2.318-.662 3.33-1.317-.386 1.256-1.248 2.312-2.4 2.942 1.048-.106 2.07-.394 3.02-.85-.458 1.182-1.343 2.15-2.48 2.71z"></path>
                      </svg>
                  </span>
                <span class="rrssb-text">{l s='twitter'  mod='mpm_socialsharebuttons'}</span>
            </a>
        </li>
    {/if}

    {if isset($buttons['button_googleplus']) && $buttons['button_googleplus']}
        <li class="rrssb-googleplus" data-initwidth="6.25" data-size="58" style="width: calc(7.69231% - 9.69231px);">
            <a href="" class="popup">
                  <span class="rrssb-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M21 8.29h-1.95v2.6h-2.6v1.82h2.6v2.6H21v-2.6h2.6v-1.885H21V8.29zM7.614 10.306v2.925h3.9c-.26 1.69-1.755 2.925-3.9 2.925-2.34 0-4.29-2.016-4.29-4.354s1.885-4.353 4.29-4.353c1.104 0 2.014.326 2.794 1.105l2.08-2.08c-1.3-1.17-2.924-1.883-4.874-1.883C3.65 4.586.4 7.835.4 11.8s3.25 7.212 7.214 7.212c4.224 0 6.953-2.988 6.953-7.082 0-.52-.065-1.104-.13-1.624H7.614z"></path>
                      </svg>
                  </span>
                <span class="rrssb-text">{l s='google+'  mod='mpm_socialsharebuttons'}</span>
            </a>
        </li>
    {/if}

    {if isset($buttons['button_linkedin']) && $buttons['button_linkedin']}
        <li class="rrssb-linkedin" data-initwidth="6.25" data-size="57" style="width: calc(7.69231% - 9.69231px);">
            <a href="" class="popup">
                  <span class="rrssb-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28">
                        <path d="M25.424 15.887v8.447h-4.896v-7.882c0-1.98-.71-3.33-2.48-3.33-1.354 0-2.158.91-2.514 1.802-.13.315-.162.753-.162 1.194v8.216h-4.9s.067-13.35 0-14.73h4.9v2.087c-.01.017-.023.033-.033.05h.032v-.05c.65-1.002 1.812-2.435 4.414-2.435 3.222 0 5.638 2.106 5.638 6.632zM5.348 2.5c-1.676 0-2.772 1.093-2.772 2.54 0 1.42 1.066 2.538 2.717 2.546h.032c1.71 0 2.77-1.132 2.77-2.546C8.056 3.593 7.02 2.5 5.344 2.5h.005zm-2.48 21.834h4.896V9.604H2.867v14.73z"></path>
                      </svg>
                  </span>
                <span class="rrssb-text">{l s='linkedin'  mod='mpm_socialsharebuttons'}</span>
            </a>
        </li>
    {/if}

    {if isset($buttons['button_email']) && $buttons['button_email']}
        <li class="rrssb-email" data-initwidth="6.25" data-size="37" style="width: calc(7.69231% - 9.69231px);">
            <a href="">
                  <span class="rrssb-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M21.386 2.614H2.614A2.345 2.345 0 0 0 .279 4.961l-.01 14.078a2.353 2.353 0 0 0 2.346 2.347h18.771a2.354 2.354 0 0 0 2.347-2.347V4.961a2.356 2.356 0 0 0-2.347-2.347zm0 4.694L12 13.174 2.614 7.308V4.961L12 10.827l9.386-5.866v2.347z"></path>
                      </svg>
                  </span>
                <span class="rrssb-text">{l s='email'  mod='mpm_socialsharebuttons'}</span>
            </a>
        </li>
    {/if}

    {if isset($buttons['button_pinterest']) && $buttons['button_pinterest']}
        <li class="rrssb-pinterest" data-initwidth="6.25" data-size="67" style="width: calc(7.69231% - 9.69231px);">
            <a href="">
                  <span class="rrssb-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28">
                        <path d="M14.02 1.57c-7.06 0-12.784 5.723-12.784 12.785S6.96 27.14 14.02 27.14c7.062 0 12.786-5.725 12.786-12.785 0-7.06-5.724-12.785-12.785-12.785zm1.24 17.085c-1.16-.09-1.648-.666-2.558-1.22-.5 2.627-1.113 5.146-2.925 6.46-.56-3.972.822-6.952 1.462-10.117-1.094-1.84.13-5.545 2.437-4.632 2.837 1.123-2.458 6.842 1.1 7.557 3.71.744 5.226-6.44 2.924-8.775-3.324-3.374-9.677-.077-8.896 4.754.19 1.178 1.408 1.538.49 3.168-2.13-.472-2.764-2.15-2.683-4.388.132-3.662 3.292-6.227 6.46-6.582 4.008-.448 7.772 1.474 8.29 5.24.58 4.254-1.815 8.864-6.1 8.532v.003z"></path>
                      </svg>
                  </span>
                <span class="rrssb-text">{l s='pinterest'  mod='mpm_socialsharebuttons'}</span>
            </a>
        </li>
    {/if}

    {if isset($buttons['button_pocket']) && $buttons['button_pocket']}
        <li class="rrssb-pocket" data-initwidth="6.25" data-size="50" style="width: calc(7.69231% - 9.69231px);">
            <a href="">
                  <span class="rrssb-icon">
                      <svg width="32" height="28" viewBox="0 0 32 28" xmlns="http://www.w3.org/2000/svg">
                        <path d="M28.782.002c2.03.002 3.193 1.12 3.182 3.106-.022 3.57.17 7.16-.158 10.7-1.09 11.773-14.588 18.092-24.6 11.573C2.72 22.458.197 18.313.057 12.937c-.09-3.36-.05-6.72-.026-10.08C.04 1.113 1.212.016 3.02.008 7.347-.006 11.678.004 16.006.002c4.258 0 8.518-.004 12.776 0zM8.65 7.856c-1.262.135-1.99.57-2.357 1.476-.392.965-.115 1.81.606 2.496 2.453 2.334 4.91 4.664 7.398 6.966 1.086 1.003 2.237.99 3.314-.013 2.407-2.23 4.795-4.482 7.17-6.747 1.203-1.148 1.32-2.468.365-3.426-1.01-1.014-2.302-.933-3.558.245-1.596 1.497-3.222 2.965-4.75 4.526-.706.715-1.12.627-1.783-.034-1.597-1.596-3.25-3.138-4.93-4.644-.47-.42-1.123-.647-1.478-.844z"></path>
                      </svg>
                  </span>
                <span class="rrssb-text">{l s='pocket'  mod='mpm_socialsharebuttons'}</span>
            </a>
        </li>
    {/if}

    {if isset($buttons['button_tumblr']) && $buttons['button_tumblr']}
        <li class="rrssb-tumblr" data-initwidth="6.25" data-size="51" style="width: calc(7.69231% - 9.69231px);">
            <a href="">
                  <span class="rrssb-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28">
                        <path d="M18.02 21.842c-2.03.052-2.422-1.396-2.44-2.446v-7.294h4.73V7.874H15.6V1.592h-3.714s-.167.053-.182.186c-.218 1.935-1.144 5.33-4.988 6.688v3.637h2.927v7.677c0 2.8 1.7 6.7 7.3 6.6 1.863-.03 3.934-.795 4.392-1.453l-1.22-3.54c-.52.213-1.415.413-2.115.455z"></path>
                      </svg>
                  </span>
                <span class="rrssb-text">{l s='tumblr'  mod='mpm_socialsharebuttons'}</span>
            </a>
        </li>
    {/if}

    {if isset($buttons['button_reddit']) && $buttons['button_reddit']}
        <li class="rrssb-reddit" data-initwidth="6.25" data-size="45" style="width: calc(7.69231% - 9.69231px);">
            <a href="">
                  <span class="rrssb-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28">
                        <path d="M11.794 15.316c0-1.03-.835-1.895-1.866-1.895-1.03 0-1.893.866-1.893 1.896s.863 1.9 1.9 1.9c1.023-.016 1.865-.916 1.865-1.9zM18.1 13.422c-1.03 0-1.895.864-1.895 1.895 0 1 .9 1.9 1.9 1.865 1.03 0 1.87-.836 1.87-1.865-.006-1.017-.875-1.917-1.875-1.895zM17.527 19.79c-.678.68-1.826 1.007-3.514 1.007h-.03c-1.686 0-2.834-.328-3.51-1.005-.264-.265-.693-.265-.958 0-.264.265-.264.7 0 1 .943.9 2.4 1.4 4.5 1.402.005 0 0 0 0 0 .005 0 0 0 0 0 2.066 0 3.527-.46 4.47-1.402.265-.264.265-.693.002-.958-.267-.334-.688-.334-.988-.043z"></path>
                        <path d="M27.707 13.267c0-1.785-1.453-3.237-3.236-3.237-.792 0-1.517.287-2.08.76-2.04-1.294-4.647-2.068-7.44-2.218l1.484-4.69 4.062.955c.07 1.4 1.3 2.6 2.7 2.555 1.488 0 2.695-1.208 2.695-2.695C25.88 3.2 24.7 2 23.2 2c-1.06 0-1.98.616-2.42 1.508l-4.633-1.09c-.344-.082-.693.117-.803.454l-1.793 5.7C10.55 8.6 7.7 9.4 5.6 10.75c-.594-.45-1.3-.75-2.1-.72-1.785 0-3.237 1.45-3.237 3.2 0 1.1.6 2.1 1.4 2.69-.04.27-.06.55-.06.83 0 2.3 1.3 4.4 3.7 5.9 2.298 1.5 5.3 2.3 8.6 2.325 3.227 0 6.27-.825 8.57-2.325 2.387-1.56 3.7-3.66 3.7-5.917 0-.26-.016-.514-.05-.768.965-.465 1.577-1.565 1.577-2.698zm-4.52-9.912c.74 0 1.3.6 1.3 1.3 0 .738-.6 1.34-1.34 1.34s-1.343-.602-1.343-1.34c.04-.655.596-1.255 1.396-1.3zM1.646 13.3c0-1.038.845-1.882 1.883-1.882.31 0 .6.1.9.21-1.05.867-1.813 1.86-2.26 2.9-.338-.328-.57-.728-.57-1.26zm20.126 8.27c-2.082 1.357-4.863 2.105-7.83 2.105-2.968 0-5.748-.748-7.83-2.105-1.99-1.3-3.087-3-3.087-4.782 0-1.784 1.097-3.484 3.088-4.784 2.08-1.358 4.86-2.106 7.828-2.106 2.967 0 5.7.7 7.8 2.106 1.99 1.3 3.1 3 3.1 4.784C24.86 18.6 23.8 20.3 21.8 21.57zm4.014-6.97c-.432-1.084-1.19-2.095-2.244-2.977.273-.156.59-.245.928-.245 1.036 0 1.9.8 1.9 1.9-.016.522-.27 1.022-.57 1.327z"></path>
                      </svg>
                  </span>
                <span class="rrssb-text">{l s='reddit'  mod='mpm_socialsharebuttons'}</span>
            </a>
        </li>
    {/if}

    {if isset($buttons['button_hackernews']) && $buttons['button_hackernews']}
        <li class="rrssb-hackernews" data-initwidth="6.25" data-size="89" style="width: calc(7.69231% - 9.69231px);">
            <a href="">
                  <span class="rrssb-icon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28">
                        <path fill="#FFF" d="M14 13.626l-4.508-9.19H6.588l6.165 12.208v6.92h2.51v-6.92l6.15-12.21H18.69"></path>
                      </svg>
                  </span>
                <span class="rrssb-text">{l s='hackernews'  mod='mpm_socialsharebuttons'}</span>
            </a>
        </li>
    {/if}

</ul>