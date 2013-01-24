<?php

	$siteUrl = get_home_url();

    	$siteUrl = str_replace('http://','',$siteUrl);

    	$siteUrl = str_replace('https://','',$siteUrl);

	


	// default values for Checkout Page

	$smlBaseUrl = 'http://www.shopmywindow.com/';

 	get_header( $name ); 

 	$smlCheckoutUrl = '/embed/checkout-login-embed'

 ?> 

<script type="text/javascript" >

	document.title = "<?php bloginfo('name')  ?> | Checkout";

</script>

<div id="smlCartClearDiv">

	<iframe id="smlIframeBase" class="smlCheckoutIframe" src="<?php echo $smlBaseUrl.$smlCheckoutUrl ?>" frameborder="0" scrolling="no" seamless="seamless" marginheight="0" marginwidth="0"></iframe>

</div>

<link href="/wp-content/plugins/sml_shopkeeper/sml_shopkeeper.css" rel="stylesheet" type="text/css" />

<?php get_footer( $name ); ?> 