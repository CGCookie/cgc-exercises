jQuery(document).ready(function($){

	$('#cgc-edu-tabs').organicTabs();

	//vars
	var ajaxurl			= cgc_exercise_meta.ajaxurl,
		nonce 			= cgc_exercise_meta.nonce,
		modal 			= $('#cgc-grading-modal'),
		exercise_modal  = $('#cgc-exercise-submission-modal'),
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

    // image upload ajax
    var options = {
        target:        results,
        beforeSubmit:  showRequest,
        success:       showResponse,
        url:    		ajaxurl
    };

    // bind form using 'ajaxForm'
    $('#cgc-exercise-submit-form').ajaxForm(options);

    function showRequest(formData, jqForm, options) {
		$(results).html('Sending...');
	}
	function showResponse(responseText, statusText, xhr, $form)  {
		$(results).hide();
		$(results).html(responseText);
		$(results).fadeIn();

		$(exercise_modal).find('h2').text('Congrats!');
  		$(exercise_modal).find('.cgc-universal-modal--intro').text('Congrats on submitting your work to be graded by the community. That is huge first step in becoming better. Take a moment to share out your work or grade others in the exercise.')
  		$(exercise_modal).find('form').fadeOut()
  		$(exercise_modal).find(results).after('<a href="">Share</a>');
	}
});

