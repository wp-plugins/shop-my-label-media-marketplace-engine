<?php
	/***
		We check the option settings for sml-secure
		If they are secure then redirect to secure and use secure url
		We then add the iframe between the template header and footer
	***/
	$isSMLSecure =  get_option('sml-mme-ssl-setting');
	$smlCartUrl = get_option('sml-mme-cart-dev');
	if($isSMLSecure === 'on'){
		$siteUrl = get_home_url();
		$siteUrl = str_replace('http://','',$siteUrl);
		$siteUrl = str_replace('https://','',$siteUrl);
		$smlCartUrl = get_option('sml-mme-secure-cart-dev');
		if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == ""){
			$redirect = "https://".$siteUrl."/cart_dev";
			header("Location: $redirect");
		}
	}
?>
<?php  get_header( $name ); ?> 
<script type="text/javascript" >
	document.title = "<?php bloginfo('name')  ?> | Shopping Cart";
</script>
<div id="smlCartClearDiv">
	<iframe id="smlIframeBase" class="smlCartIframe" src="<?php echo $smlCartUrl ?>" frameborder="0" scrolling="auto" seamless="seamless" marginheight="0" marginwidth="0"></iframe>
</div>
<link href="/wp-content/plugins/shop-my-label-media-marketplace-engine/sml_shopkeeper.css" rel="stylesheet" type="text/css" />
<?php get_footer( $name ); ?> 