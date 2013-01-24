 <?php 

	// default values for Product Detail Page

	$smlBaseUrl = 'http://www.shopmywindow.com/';

	$shopKeeper = $_GET['c'];

	$shopWindow = $_GET['p'];

	$productDetail = $smlBaseUrl.'/embedproductdialog/'.$shopKeeper.'/'.$shopWindow;

?>

<script type="text/javascript" >

	document.title = "<?php bloginfo('name')  ?> | Product Detail";

</script>

<link href="/wp-content/plugins/sml_shopkeeper/sml_shopkeeper.css" rel="stylesheet" type="text/css" />

<div style="clear; text-align:center;">

	<iframe id="smlIframeBase" src="<?php echo $productDetail ?>" class="smlProdDetailIframe" frameborder="0" scrolling="no"  seamless="seamless" marginheight="0" marginwidth="0"></iframe>

</div>