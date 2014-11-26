jQuery(document).ready(function($){

	$('#cgc-edu-tabs').organicTabs();

	//vars
	var ajaxurl			= cgc_exercise_meta.ajaxurl,
		nonce 			= cgc_exercise_meta.nonce,
		modal 			= $('#cgc-grading-modal'),
		results         = $('#cgc-edu-exercise--submission-results');

	// trigger the click when they vote
	$('#cgc-exercise-vote-form label').click(function( ){
		//e.preventDefault();
		$(this).next('input').attr('checked', true);
		$('#cgc-exercise-vote').trigger('click');
	});

	// vote click handler
  	$('#cgc-exercise-vote-form').submit(function(e) {

  		e.preventDefault();

  		var data = $(this).serialize();

	  	$.post(ajaxurl, data, function(response) {
	  		$('#cgc-edu-exercise--vote-results').html(response);
	    });

	    $(modal).reveal();
    });

    $('.comment-cancel').click(function(){
    	location.reload();
    });

	// submission click handler
  	$('#cgc-exercise-submit-form').submit(function(e){

  		e.preventDefault();

  		var data = $(this).serialize();

	  	$.post(ajaxurl, data, function(response) {
	  		$(results).hide();
	  		$(results).html(response);
	  		$(results).fadeIn();
	  		$('#cgc-exercise-submit-form').fadeOut();
	    });
    });
});