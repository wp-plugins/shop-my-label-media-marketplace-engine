<?php
/*
Plugin Name: Shop My Label Shopkeeper Plugin
Plugin URI: http://www.shopmylabel.com
Description: This plugin will add a few urls to your blog to handle adding <strong>MME</strong> to your blog. The only URL you may want to add is  <strong>{blogname}/cart</strong>.  This will allow clients to checkout from your blog using the MME.  The plugin will give clients an option to go to this link after adding an item to their cart.   This plugin requires permalinks  turned on, mod_rewrite (if hosted locally), and no categories named in conflict with the newly rewritten URLs.
Version: 1.2
Author: Team Shop My Label
Author URI: http://www.shopmylabel.com/mme
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



        "url" => 'http://www.shopmylabel.com/'



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



$smlWindow = '<script>$("#smlWrapper").remove();</script>';    



$smlWindow .= '<div id="smlWrapper"><div id="sml_product"><img src="/wp-content/plugins/sml_shopkeeper/sml_close.png" class="sml_close_button" /><div id="smlLoader"></div><iframe  id="sml_iframe" scr="/sml_loader" class="large" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe></div></div>';



    $smlWindow .= '<table><td><div class="shopWindowWrapper_'.$size.'"><div  class="'.$size.'">'.$windowContent.'</div></div></td></table>';



    $smlWindow .='<link href="/wp-content/plugins/sml_shopkeeper/sml_shopkeeper.css" rel="stylesheet" type="text/css" />';



    $smlWindow .='<script src="/wp-content/plugins/sml_shopkeeper/sml_popup.js" type="text/javascript"></script>';



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
/***
	Excludes the cart pages from WP redirection
***/
add_filter('redirect_canonical', 'sml_cart_redirect', 10, 2);
function sml_cart_redirect($redirect_url, $requested_url) {
	if((substr($requested_url, -5) =='/cart')||(substr($requested_url, -9) =='/cart_dev')){
		return $requested_url;
  	} else {
    		return $redirect_url;
  	}

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
          "cart_dev" => "wp-content/plugins/sml_shopkeeper/sml_cart_dev.php"
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