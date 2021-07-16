$(document).ready(function() {
	
    //
	$('#system').click(function(e){
		e.preventDefault()
		e.stopPropagation();
		/*$('#slider').hide('fast');
		$('.content-box').hide('fast');
		$('.under-content').hide('fast');
		$('.web-development').hide('fast');
		$('.networking').hide('fast');
		$('.system').show('slow');*/
		 window.location.href='system/';
		
	});
	
	$('#web').click(function(e){
		e.preventDefault()
		e.stopPropagation();
		/*$('#slider').hide('fast');
		$('.content-box').hide('fast');
		$('.under-content').hide('fast');
		$('.networking').hide('fast');
		$('.system').hide('fast');
		$('.web-development').show('slow');*/
		window.location.href='web/';
	});
	
	$('#networking').click(function(e){
		e.preventDefault();
		e.stopPropagation();
		/*$('#slider').hide('fast');
		$('.content-box').hide('fast');
		$('.under-content').hide('fast');
		$('.web-development').hide('fast');
		$('.system').hide('fast');
		$('.networking').show('slow');*/
		window.location.href='networking/';
	});
	
	$('#car').click(function(e){
		e.preventDefault();
		e.stopPropagation();
		/*$('#slider').hide('fast');
		$('.content-box').hide('fast');
		$('.under-content').hide('fast');
		$('.web-development').hide('fast');
		$('.system').hide('fast');
		$('.networking').show('slow');*/
		window.location.href='car-hire/';
	});
});