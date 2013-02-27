<?php
/*
Plugin Name: Shop My Label Shopkeeper Plugin
Plugin URI: http://www.shopmylabel.com
Description: This plugin will add a few urls to your blog to handle adding <strong>MME</strong> to your blog. The only URL you may want to add is  <strong>{blogname}/cart</strong>.  This will allow clients to checkout from your blog using the MME.  The plugin will give clients an option to go to this link after adding an item to their cart.   This plugin requires permalinks  turned on, mod_rewrite (if hosted locally), and no categories named in conflict with the newly rewritten URLs.
Version: 1.3.5
Author: Team Shop My Label
Author URI: http://www.shopmylabel.com/mme
License: GPLv2 or later
*/

/***
    adding shortcode for blog[sml url size]
    We extract the content from the shortcode to get the url and size.  We then check and clean the strings
    From that we build the html content for the embeded window.
***/
add_shortcode("sml", "smlwindow");
function smlwindow($atts, $content = null) {
    $siteUrl = get_home_url();
    $siteUrl = str_replace('http://','',$siteUrl);
    $siteUrl = str_replace('https://','',$siteUrl);
    extract(shortcode_atts(array(
        "size"=>"large",
        "url" => 'http://www.shopmylabel.com/'
    ), $atts));
    if($size == 'small'){
        // size is small
    }elseif($size == 'medium'){
        // size is medium
    }else{
        // default to large
        $size = 'large';        
    }
    $fix = str_replace('/stores/', '/embed/', $url);
    $embed = str_replace('/window/', '/', $fix);
    $curlUrl = $embed.'/'.$siteUrl;
    $windowContent = getSmlWindow($curlUrl);
    $smlWindow = '<script>window.jQuery || document.write(\'<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"><\/script>\')</script>';  
    $smlWindow .= '<table><td><div class="shopWindowWrapper_'.$size.'"><div  class="'.$size.'">'.$windowContent.'</div></div></td></table>';
    $smlWindow .='<link href="/wp-content/plugins/shop-my-label-media-marketplace-engine/sml_shopkeeper.css" rel="stylesheet" type="text/css" />';
    $smlWindow .='<script src="/wp-content/plugins/shop-my-label-media-marketplace-engine/sml_popup.js" type="text/javascript"></script>';
    return $smlWindow;
}

/***
    We get the window content as HTML and then add it into the post as content from this site
    This avoids cross site JS issues and delay loading because of slow iframes
    All the HTML content from each window is coming directly from the SML servers and added inline
***/
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

/***
    We add the sub menu under settings for the SML SSL Options
    We both add the sub menu action and then the submenu page
    We then add the default setting to be false, so SSL is off
    We also add the default values for the cart urls
***/
add_action( 'admin_menu', 'sml_mme_admin_menu' );
add_action( 'admin_init', 'sml_mme_my_setting' );
function sml_mme_my_setting() {
    update_option( 'sml-mme-secure-cart', 'https://www.shopmylabel.com/embed/shoppingcart' );
    update_option( 'sml-mme-secure-cart-dev', 'https://www.shopmywindow.com/embed/shoppingcart' );
    update_option( 'sml-mme-cart', 'http://www.shopmylabel.com/embed/shoppingcart?noSecure=true' );
    update_option( 'sml-mme-cart-dev', 'http://www.shopmywindow.com/embed/shoppingcart?noSecure=true' );
    if(get_option('sml-mme-ssl-setting') != 'on'){
        update_option( 'sml-mme-ssl-setting', '' );
    }
    register_setting('sml-mme-options', 'sml-mme-ssl-setting');
    register_setting('sml-mme-options', 'sml-mme-cart-dev');
    register_setting('sml-mme-options', 'sml-mme-cart');
    register_setting('sml-mme-options', 'sml-mme-secure-cart-dev');
    register_setting('sml-mme-options', 'sml-mme-secure-cart');
} 

function sml_mme_admin_menu() {
  add_options_page( ' Shop-My-Label-MME', 'Shop My Label MME', 'manage_options', 'shop-my-label-media-marketplace-engine', 'sml_mme_options' );
}

function sml_mme_options() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    echo '<div class="wrap">';
    echo screen_icon();
    echo '<h2>Shop My Label MME SSL Settings</h2>';
    echo'<form method="post" action="options.php">';
    settings_fields('sml-mme-options');
    do_settings_fields('sml-mme-options','sml-mme-ssl-setting');
    $sslSetting =  get_option('sml-mme-ssl-setting');
    $checkbox = '';
    if($sslSetting === 'on'){
        $checkbox = '<input type="checkbox" checked="checked" name="sml-mme-ssl-setting">';
    }else{
        $checkbox = '<input type="checkbox" name="sml-mme-ssl-setting">';
    }
    echo '<input name="action" type="hidden" value="update" />';
    echo '<p>'.$checkbox.' Enable on-site checkout (requires SSL)" <em>off by default</em>.</p>';
    echo  '<p>Please visit <a href="http://www.shopmylabel.com/mme" target="_blank">www.shopmylabel.com/mme</a> for more information about getting SSL security</p>';
    echo  submit_button();
    echo '</form>';
    echo '</div>';
}

/***
    We add the redirect rules for cart and cart_dev
    These pages are not posts, so they will not be found
    We also exclude the redirect if in admin    
***/
add_filter('generate_rewrite_rules', 'sml_flush_rules');
add_action('template_redirect', 'sml_redirect');
function sml_flush_rules() {    
    if (is_admin()){
        return;
    }
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}       

/***
    WP will defaulty try to correct misspelled URLS.  Since the Cart and Cart_dev do not exist, they will be considered unfound and corrected.
    Here we capture the request for correction and check if it is the cart or cart_dev
    If that is the case, we do not redirect, or if it is, then we continue and allow the normal WP redirect
***/
add_filter('redirect_canonical', 'sml_cart_redirect', 10, 2);
function sml_cart_redirect($redirect_url, $requested_url) {
    if((substr($requested_url, -5) =='/cart')||(substr($requested_url, -9) =='/cart_dev')){
      return $requested_url;
    } else {
        return $redirect_url;
    }
}

/***
    rewrite rules are set in array that can be extended for more custom pages
    The sml_url_rewrite sets 2 urls that will be rewritten to the sml_pluggin folder
    The request is filtered against this array and if found then redirect to that page
    If the request is not found then it is ignored and continues down the "wp routing table"
***/
function sml_redirect() {
    global $wp;
    global $sml_url_rewrite;
    if (!is_array($sml_url_rewrite)) {
        $sml_url_rewrite = array(
          "cart" => "wp-content/plugins/shop-my-label-media-marketplace-engine/sml_cart.php",
          "cart_dev" => "wp-content/plugins/shop-my-label-media-marketplace-engine/sml_cart_dev.php"
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