<?php



/*



Plugin Name: Shop My Label Shopkeeper Plugin



Plugin URI: http://www.shopmylabel.com



Description: This plugin will add 3 urls to your blog to handle adding <strong>Shop My Label Windows</strong> to your blog. These URLs will be <strong>{blogname}/cart</strong>, <strong>{blogname}/checkout</strong>, and <strong>{blogname}/product-details</strong>.  This plugin requires permalinks  turned on, mod_rewrite (if hosted locally), and no categories named in conflict with the newly rewritten URLs.



Version: 1



Author: Team Shop My Label



Author URI: http://www.shopmylabel.com/about/team



License: See http://www.shopmylabel.com for License agreement



*/



function getSmlWindow($windowUrl) {



  $ch = curl_init();



  $timeout = 5;



  curl_setopt($ch, CURLOPT_URL, $windowUrl);



  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);



  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);



  $data = curl_exec($ch);



  curl_close($ch);



  return $data;



}







function smlwindow($atts, $content = null) {



$siteUrl = get_home_url();



$siteUrl = str_replace('http://','',$siteUrl);



$siteUrl = str_replace('https://','',$siteUrl);







extract(shortcode_atts(array(



  "size"=>"large",



  "url" => ''



  ), $atts));







if($size == 'small'){



    



}elseif($size == 'medium'){



      



}else{



    $size = 'large';        



}



$fix = str_replace('/stores/', '/embed/', $url);



$embed = str_replace('/window/', '/', $fix);



$curlUrl = $embed.'/'.$siteUrl;





$windowContent = getSmlWindow($curlUrl);



  







$smlWindow = '<div id="smlWrapper"><div id="sml_product"><iframe  id="sml_iframe" scr="" class="large" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe></div></div>';



$smlWindow .= '<div class="shopWindowWrapper_'.$size.'"><div  class="'.$size.'">'.$windowContent.'</div></div>';



$smlWindow .='<link href="/wp-content/plugins/sml_shopkeeper/sml_shopkeeper.css" rel="stylesheet" type="text/css" />';

$smlWindow .='<script src="/wp-content/plugins/sml_shopkeeper/sml_shopkeeper.js" ></script>';











return $smlWindow;



}







// adding shortcode for blog[sml url size]



add_shortcode("sml", "smlwindow");











add_filter('generate_rewrite_rules', 'sml_flush_rules');



add_action('template_redirect', 'sml_redirect');







function sml_flush_rules() {    



/***



    No rewrite within wp-admin



***/  



  if (is_admin()){



        return;



  }



  global $wp_rewrite;



  $wp_rewrite->flush_rules();



}       











add_filter('query_vars', 'parameter_queryvars' );



function parameter_queryvars( $qvars )



{



$qvars[] = ' myvar';



    return $qvars;



}















function sml_redirect() {



/***



    rewrite rules are set in array that can be extended for more custom pages



    The sml_url_rewrite sets 3 urls that will be rewritten to the sml_pluggin folder



    The request is filtered against this array and if found then redirect to that page



    If the request is not found then it is ignored and continues down the "wp routing table"



***/



  global $wp;



  global $sml_url_rewrite;



  if (!is_array($sml_url_rewrite)) {



    $sml_url_rewrite = array(



      "cart" => "wp-content/plugins/sml_shopkeeper/sml_cart.php",



      "product-details" => "wp-content/plugins/sml_shopkeeper/sml_product_detail.php",



      "checkout" => "wp-content/plugins/sml_shopkeeper/sml_checkout.php",



      "sml_style/style.css" => "wp-content/plugins/sml_shopkeeper/sml_shopkeeper.css",



      "test_frame" => "wp-content/plugins/sml_shopkeeper/test_frame.html"



   );



  }



  if (isset($sml_url_rewrite[$wp->request])) {  



    $file = $sml_url_rewrite[$wp->request];



    if (is_file($file)) {



      header('HTTP/1.1 200 OK');



      require $file;



      exit;



    }



  }



}