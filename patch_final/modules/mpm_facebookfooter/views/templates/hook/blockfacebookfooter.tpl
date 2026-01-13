
{if $url != ''}
	<div id="facebook_block" class="footer-block col-sm-2 text-uppercase links ">
		<h3 class="h3 hidden-sm-down">{l s='Follow us on Facebook' mod='mpm_facebookfooter'}</h3>
		<div class="title clearfix hidden-md-up" data-target="#footer_sub_menu_facebook" data-toggle="collapse">
			<span class="h3">{l s='Follow us on Facebook'  mod='mpm_facebookfooter'}</span>
			<span class="pull-xs-right">
			  <span class="navbar-toggler collapse-icons">
				<i class="material-icons add"></i>
				<i class="material-icons remove"></i>
			  </span>
			</span>
		</div>

		<div class="collapse" id="footer_sub_menu_facebook">
			<div class="fb-page" data-href="{$url|escape:'html':'UTF-8'}" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
				<blockquote cite="{$url|escape:'html':'UTF-8'}" class="fb-xfbml-parse-ignore">
					<a href="{$url|escape:'html':'UTF-8'}"></a>
				</blockquote>
			</div>
		</div>
	</div>

	<script>(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>
{/if}
