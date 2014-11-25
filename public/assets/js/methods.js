jQuery(document).ready(function($){

	$('#cgc-edu-tabs').organicTabs();

	//vars
	var ajaxurl			= cgc_exercise_meta.ajaxurl,
		nonce 			= cgc_exercise_meta.nonce,
		modal 			= $('#cgc-grading-modal');

	// trigger the click when they vote
	$('#cgc-exercise-vote-form label').click(function(e){
		e.preventDefault();
		$('#cgc-exercise-vote').trigger('click');
		$(modal).reveal();
	});

	// vote click handler
  	$('#cgc-exercise-vote-form').submit(function(e) {

  		e.preventDefault();

  		var data = $(this).serialize();

	  	$.post(ajaxurl, data, function(response) {
	  		$('#cgc-edu-exercise--vote-results').html(response);
	    });
    });

	// submission click handler
  	$('#cgc-exercise-submit').click(function(e){

  		e.preventDefault();

  		var data = {
            action: 'process_submission',
            nonce: nonce
        };

	  	$.post(ajaxurl, data, function(response) {
	  		alert(response);
	    });
    });
});