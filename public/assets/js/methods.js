jQuery(document).ready(function($){

	$('#cgc-edu-tabs').organicTabs();

	//vars
	var ajaxurl			= cgc_exercise_meta.ajaxurl,
		nonce 			= cgc_exercise_meta.nonce

	// vote click handler
  	$('#cgc-exercise-vote-form').submit(function(e) {

  		e.preventDefault();

  		var data = {
            action: 'process_grading',
            nonce: nonce
        };

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