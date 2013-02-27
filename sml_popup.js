jQuery('#sml_product').hover(

  function(){

    // do nothing     







  },function(){







    if(insideSmlFrame !== true){







      //setTimeout('hidePopUP()',500);











    }







  }







);















function hidePopUP(){







  if(insideSmlFrame !== true){







    if(jQuery('#smlWrapper').is(":visible")){







        jQuery('#smlWrapper').fadeOut();

        jQuery('#right').fadeIn();







    }







  }







}















function fullScreenIt(el) {







    "use strict";







    var scrollIt = function () {







        var winWidth = jQuery(window).width();







        var winHeight = jQuery(window).height();







        var docHeight = jQuery(document).scrollTop();







        var paddingHeight = docHeight - winHeight;







        el.css("height", docHeight+winHeight+"px");







        //console.log(winHeight +'/'+docHeight  +'/'+paddingHeight ); 







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















fullScreenIt(jQuery('#smlWrapper'));







var insideSmlFrame = false;







var smlHoverTrue = function(e) {







    if (insideSmlFrame !== true){







        insideSmlFrame = true;







    }







};















var smlHoverFalse = function() {







    if (insideSmlFrame === true){







        insideSmlFrame = false;







    }







};







jQuery("#sml_iframe").mouseout(smlHoverFalse);







jQuery("#sml_iframe").mouseover(smlHoverTrue);







jQuery("#sml_iframe, .sml_close_button").click(function(){







  jQuery('#smlWrapper').fadeOut();

  jQuery('#right').fadeIn();





});



