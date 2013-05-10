$(document).ready(function() {
	
// tabs 
$("ul.tabs").tabs("> .tabcontent", {
			tabs: 'li', 
			effect: 'fade'
		});

// toggle content
	$(".toggle_content").hide(); 
        
        $(".toggle").click(function() {
                $(this).next(".toggle_content").slideToggle(300,'easeInQuad');
		$(this).toggleClass("active");
                return false;
            });
        
        $(".toggle_content").mouseup(function() {
                return false
        });
        $(document).mouseup(function(e) {
                if(!$(e.target).is(".active")) {
                    $(".toggle").removeClass("active");
                    $(".toggle_content").slideUp(300,'easeInQuad');  
                }
        });



// buttons	
	if (!$.browser.msie) {
		$(".button_styled").hover(function(){
			$(this).stop().animate({"opacity": 0.8});
		},function(){
			$(this).stop().animate({"opacity": 1});
		});
		$(".button_link").hover(function(){
			$(this).stop().animate({"opacity": 0.8});
		},function(){
			$(this).stop().animate({"opacity": 1});
		});
	}

});
// scroll to top
$(function () {  
     $(window).scroll(function () {  
         if ($(this).scrollTop() != 0) {  
             $('.link-top').fadeIn();  
         } else {  
             $('.link-top').fadeOut();  
         }  
     });  
     $('.link-top').click(function () {  
         $('body,html').animate({  
             scrollTop: 0  
         },  
         1500);  
     });  
 });
