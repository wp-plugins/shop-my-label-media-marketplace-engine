$('#sml_product').hover(

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

		if($('#smlWrapper').is(":visible")){

				$('#smlWrapper').fadeOut();

		}

	}

}



function fullScreenIt(el) {

    "use strict";

    var scrollIt = function () {

        var winWidth = $(window).width();

        var winHeight = $(window).height();

        var docHeight = $(document).scrollTop();

        var paddingHeight = docHeight - winHeight;

        el.css("height", docHeight+winHeight+"px");

        //console.log(winHeight +'/'+docHeight  +'/'+paddingHeight ); 

	if(((0-paddingHeight) >= 100) || paddingHeight >-100){

            $('#smlWrapper').css('padding-top',docHeight+'px');

        }else{

		$('#smlWrapper').css('padding-top','100px');

	}

    };

    $(window).resize(scrollIt);

    $(window).scroll(scrollIt);

    scrollIt();

}



fullScreenIt($('#smlWrapper'));

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

$("#sml_iframe").mouseout(smlHoverFalse);

$("#sml_iframe").mouseover(smlHoverTrue);

$("#sml_iframe, .sml_close_button").click(function(){

	$('#smlWrapper').fadeOut();

});