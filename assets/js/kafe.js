$(window).load(function () {
	
		/* VEGAS Home Slider */	
			$(".se-pre-con").fadeOut('slow', function() {$(this).remove();});
	
});

$(document).ready(function() {
						 
	// navigation click actions	
	// jQuery for page scrolling feature - requires jQuery Easing plugin

    $('.scroll-link').bind('click', function(event) {
        var $anchor = $(this);
	    var offSet = 85;
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top - offSet
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
    });
	
						   
	/*============================================
	Navigation Functions
	==============================================*/
	if ($(window).scrollTop()===0){
		$('.navbar').removeClass('scrolled');
	}
	else{
		$('.navbar').addClass('scrolled');    
	}

	$(window).scroll(function(){
		if ($(window).scrollTop()===0){
			$('.navbar').removeClass('scrolled');
		}
		else{
			$('.navbar').addClass('scrolled');    
		}
	});
	
	/*============================================
	Scroll To Top
	==============================================*/	

	//When distance from top = 250px fade button in/out
	$(window).scroll(function(){
		if ($(this).scrollTop() > 250) {
			$('#scrollup').fadeIn(300);
		} else {
			$('#scrollup').fadeOut(300);
		}
	});

	//On click scroll to top of page t = 1000ms
	$('#scrollup').click(function(){
		$("html, body").animate({ scrollTop: 0 }, 1000);
		return false;
	});	
	
	/*============================================
	Typed Functions
	==============================================*/	
	//   $("#typed").typed({
    //     stringsElement: $('#typed-strings'),
	// 	typeSpeed: 0,
    //     contentType: 'html',
	// 	showCursor: false
	//   });
	  
	/*============================================
	Appear JS
	==============================================*/	
    if ($.fn.appear) {		
        $('.number-animator').appear();
        $('.number-animator').on('appear', function () {
            $(this).animateNumbers($(this).attr("data-value"), true, parseInt($(this).attr("data-animation-duration")));
        });

        $('.animated-progress-bar').appear();
        $('.animated-progress-bar').on('appear', function () {
            $(this).css('width','0%').animate({ 'width': $(this).attr("data-percentage") }, 1000);
        });
    }

	/*============================================
	Animate Numbers
	==============================================*/
    if ($.fn.animateNumbers) {
        $('.animate-number').each(function () {
            $(this).animateNumbers($(this).attr("data-value"), true, parseInt($(this).attr("data-animation-duration")));
        })
    }	  

});

