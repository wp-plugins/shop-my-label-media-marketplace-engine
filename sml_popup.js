/***
    We center the popup window based on the screen size
    On resize we reset the sizing so it is always centered
    The fadeout is always covering the screen
***/
function fullScreenIt(el) {
    var scrollIt = function () {
        var winWidth = jQuery(window).width();
        var winHeight = jQuery(window).height();
        var docHeight = jQuery(document).scrollTop();
        var paddingHeight = docHeight - winHeight;
        el.css("height", docHeight+winHeight+"px");
        if(((0-paddingHeight) >= 100) || paddingHeight >-100){
            jQuery('#smlWrapper').css('padding-top',docHeight+'px');
        }else{
            jQuery('#smlWrapper').css('padding-top','100px');
        }
    };
    jQuery(window).resize(scrollIt);
    jQuery(window).scroll(scrollIt);
    scrollIt();
}

/***
    We set the sml buttons for close
    We set the wrapper to close
    We show the right side adds

***/

function setSMLButtons(){
    jQuery("#smlWrapper, .sml_close_button").click(function(){
        jQuery('#smlWrapper').fadeOut();
        jQuery('#right').fadeIn();
    });
}

/***
    We add the wrapper and container files to be a child of the body
    This prevents any issues of positioning children
    This will also prevent the issue of preloading the items and having mulitple scripts firing
***/
var smlFrame = '<div id="smlWrapper"><div id="sml_product"><img src="/wp-content/plugins/shop-my-label-media-marketplace-engine/sml_close.png" class="sml_close_button"><div id="smlLoader"></div><iframe id="sml_iframe" scr="/sml_loader" class="large" marginheight="0" marginwidth="0" frameborder="0" scrolling="no"></iframe></div></div>';

/***
    When the document is ready we check if there is any wrappers then add them if needed
    We prevent muliple loads if there are muliple windows
    Once set, we set the buttons

***/

jQuery(document).ready(function(){
    if(jQuery('#smlWrapper').length < 1){
        jQuery('body').prepend(smlFrame);
        fullScreenIt(jQuery('#smlWrapper'));
    }
    setSMLButtons();
});