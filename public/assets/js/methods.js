jQuery(document).ready(function($){

	$('#cgc-edu-tabs').organicTabs();

	//vars
	var ajaxurl			= cgc_exercise_meta.ajaxurl,
		nonce 			= cgc_exercise_meta.nonce,
		modal 			= $('#cgc-grading-modal'),
		exercise_modal  = $('#cgc-exercise-submission-modal'),
		results         = $('#cgc-edu-exercise--submission-results');

	// trigger the click when they vote
	$('#cgc-exercise-vote-form input').click(function(){
		//e.preventDefault();
		$(this).attr('checked', true);
		$('#cgc-exercise-vote-form').trigger('submit');

	});

	// vote click handler
  	$('#cgc-exercise-vote-form').submit(function(event) {

  		event.preventDefault();

  		var data = $(this).serialize();

	  	$.post(ajaxurl, data, function(response) {
	  		$('#cgc-edu-exercise--vote-results').html(response);
	    });

	    $(modal).reveal();

    });

    $('.comment-cancel').click(function(e){
    	e.preventDefault();
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


    // validation
    $('#cgc-exercise-submit-form').submit(function(e){


    	$('.exercise-field-required').each(function(){

	    	if ( $(this).val() == '' ) {

	    		e.preventDefault();
	        	$(results).text('All fields required!');
	        	$(this).css({'border':'1px solid #d9534f'});
	        	$('#cgc-edu-exercise--submission-results').addClass('error');
	        	return false;

       	 	}
       	});
    });

	$('.exercise-field-required').each(function(){
		$(this).keyup(function(event) {
		    var input = $(this);
		    var message = $(this).val();
		    console.log(message);
		    if(message){input.removeClass("invalid").addClass("valid");}
		    else{input.removeClass("valid").addClass("invalid");}
		});
	});

    $('#cgc-exercise-submit-form').submit(function(e){

    	if ( $('.exercise-field-required').val() == '' ) {

    		e.preventDefault();
        	$(results).text('All fields required!');
        	$('#cgc-exercise-submit-form textarea, #cgc-exercise-submit-form input[type="text"]').css({'border':'1px solid #d9534f'});
        	$('#cgc-edu-exercise--submission-results').addClass('error');
        	return false;

        }

    });

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
            $(exercise_modal).addClass('image-uploading');

        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            bar.width(percentVal);
            percent.html(percentVal);
        }
    });

     $('#cgc-exercise-submit-form input:file').live('change',function (){
       	var fileName = $(this).val().split('\\').pop();
       	$('.filename').text(fileName);

       	// if the file size is above 1mb then warn them with a tip, and disable the submit button until we have an approprpriate size
       	if ( this.files[0].size > 1000000 ) {
       		alert('Your image is too big! Try resizing to under 1mb using a tool like http://tinyjpg.com or http://tinypng.com.')
       		$(this).val('');
       		$('.filename').text('');
       		$('input[type="submit"').attr('disabled','disabled');
       	} else {
       		$('input[type="submit"').removeAttr('disabled')
       	}

     });

    function showRequest(formData, jqForm, options) {
		$(results).html('Sending...');
	}
	function showResponse(responseText, statusText, xhr, $form)  {

		$(results).hide();
		$(results).html(responseText);
		$(results).fadeIn();
		$(progress).fadeOut();
		$(results).removeClass('error');

		$(exercise_modal).find('h2').text('Congrats!');
  		$(exercise_modal).find('.cgc-universal-modal--intro').text('Congrats on submitting your work to be graded by the community. That is huge first step in becoming better. Take a moment to share out your work or grade others in the exercise.')
  		$(exercise_modal).find('form').fadeOut()
  		$(exercise_modal).find(results).after(cgc_exercise_meta.shareExercise);

  		$('.close-modal').live('click',function(e){
  			e.preventDefault();
  			location.reload();
  		})
	}

	// attempt to detect video source

	$('#exercise-video').live('change',function(){

		var val = $(this).val();
		var provider = check_url(val);

		$('.exercise-video-source').addClass('icon-'+provider+' ');
		$('#exercise-video-provider').val(provider)

	});

	var regYoutube = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
	var regVimeo = /^.*(vimeo\.com\/)((channels\/[A-z]+\/)|(groups\/[A-z]+\/videos\/))?([0-9]+)/;
	var regDailymotion = /^.+dailymotion.com\/(video|hub)\/([^_]+)[^#]*(#video=([^_&]+))?/;
	var regMetacafe = /^.*(metacafe\.com)(\/watch\/)(\d+)(.*)/i;
	function check_url(url) {
	    if(regYoutube.test(url)) {
	        return 'youtube';
	    }else if (regMetacafe.test(url)) {
	        return 'metacafe';
	    }else if(regDailymotion.test(url)){
	        return 'dailymotion';
	    }else if(regVimeo.test(url)) {
	        return 'vimeo';
	    }else{
	        return false;
	    }
	}

	////////////////
	// EXERCISE TILTE HELPER
	///////////////
	var titHeight = $('.cgc-edu-exercise-submission--connection a').height()

	if ( titHeight > 18 ) {
		$('.cgc-edu-exercise-submission--connection a').addClass('tall-boy')
	}

	/////////////////
	// UNITY MODAL MODS
	/////////////////
	$('a[data-reveal-id="cgc-unity-modal"').on('click',function(){
		$('body').css('overflow','hidden')
	});
	$('#cgc-unity-modal iframe').css('height', $(window).height() - 80 );

	$(window).resize(function(){
		$('#cgc-unity-modal iframe').css('height', $(window).height() - 80 );
	});

});

jQuery(window).load(function() {
    //jQuery('.cgc-exercise-submission--media').css('opacity', 1);
    jQuery('#cgc-media-loading').remove();

});
