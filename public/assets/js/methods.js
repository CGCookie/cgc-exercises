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

    // hide the comment 
    $('#cgc-grading-modal textarea').bind('input propertychange',function(){
    	$('#cgc-grading-modal #submit').addClass('active');
    });

    var bar = $('.cgc-edu-upload--bar'),
    	percent = $('.cgc-edu-upload--percent'),
    	progress = $('.cgc-edu-upload--progress')

    progress.hide();

    // bind form using 'ajaxForm'
    $('#cgc-exercise-submit-form').ajaxForm({
    	target:        results,
        beforeSubmit:  showRequest,
        success:       showResponse,
        url:    		ajaxurl,
        beforeSend: function() {
            var percentVal = '0%';
            progress.fadeIn();
            bar.width(percentVal);
            percent.html(percentVal);
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            bar.width(percentVal);
            percent.html(percentVal);
        }
    });

     $('#cgc-exercise-submit-form input:file').live('change',function (){
       var fileName = $(this).val();
       $('.filename').text(fileName);
     });

    function showRequest(formData, jqForm, options) {
		$(results).html('Sending...');
	}
	function showResponse(responseText, statusText, xhr, $form)  {
		$(results).hide();
		$(results).html(responseText);
		$(results).fadeIn();
		$(progress).fadeOut();

		$(exercise_modal).find('h2').text('Congrats!');
  		$(exercise_modal).find('.cgc-universal-modal--intro').text('Congrats on submitting your work to be graded by the community. That is huge first step in becoming better. Take a moment to share out your work or grade others in the exercise.')
  		$(exercise_modal).find('form').fadeOut()
  		$(exercise_modal).find(results).after('<a href="">Share</a>');
	}

});

jQuery(window).load(function() {
    //jQuery('.cgc-exercise-submission--media').css('opacity', 1);
    jQuery('#cgc-media-loading').remove();

});
