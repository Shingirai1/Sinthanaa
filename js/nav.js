//show and hide mobile_nav
var interval;
$(document).ready(function(){
	
	//scroll to services
	$(".Services").on('click' ,function() {
    $('html,body').animate({
        scrollTop: $(".content-box").offset().top},
        'slow');
	});
	
	$(".About_us").on('click' ,function() {
    $('html,body').animate({
        scrollTop: $(".about-us").offset().top},
        'slow');
	});
	
	$(".contact_us").on('click' ,function() {
    $('html,body').animate({
        scrollTop: $(".footer").offset().top},
        'slow');
	});
	
	//function to check width
	function checkwidth(){
		var width = $(window).width();
		if(width < 768){
			$(".nav").hide("slow");
		}else{
			$(".nav").fadeIn("slow");
		}
	}
	
	//here set interval
	interval=setInterval(checkwidth, 200);
	
	//here function to show or hide
	function slidetoggle(){
		if(!interval){
			interval=setInterval(checkwidth, 200);
			$(".nav").hide("slow");
		}else{
			clearInterval(interval);
			interval=null;
			$('.nav').slideToggle('slow');
		}
	}
	
	//here to call function on click
	$('.mobile-menu').on('click',function(){
		slidetoggle();
	});
});

//scroll to a specific DV
//$(document).ready(function(){
//$(".Services").on('click' ,function() {
//	alert("hello");
    /*$('html,body').animate({
        scrollTop: $(".content-box").offset().top},
        'slow');*/
//});
//});