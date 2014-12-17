<?php


/**
*	Used on the front end to properly escape attributes where users have control over what input is entered
*	as well as through a callback upon saving in the backend
*
*	@since 1.0
*	@return a sanitized string
*/
function cgc_edu_media_filter( $input = '' ) {

	// bail if no input
	if ( empty( $input ) )
		return;

	// setup our array of allowed content to pass
	$allowed_html = array(
		'a' 			=> array(
		    'href' 		=> array(),
		    'title' 	=> array(),
		    'rel'		=> array(),
		    'target'	=> array(),
		    'name' 		=> array()
		),
		'img'			=> array(
			'src' 		=> array(),
			'alt'		=> array(),
			'title'		=> array()
		),
		'p'				=> array(),
		'br' 			=> array(),
		'em' 			=> array(),
		'strong' 		=> array()
	);

	$out = wp_kses( $input, $allowed_html );

	return $out;
}

/**
*
*	Draws teh author block used on exercise and exercise submission templates
*
*	@param $postid int id of the post to pull author data from
* 	@param $submission bool are we on a submission template or not
* 	@return a block of actions for an author
*/

function cgc_edu_author_block( $postid = 0, $submission = false ) {

	if ( empty( $postid ) )
		$postid = get_the_ID();


	$auth_id 	= get_the_author_meta('ID');
	$user_id    = get_current_user_ID();
	$avatar 	= get_user_meta($auth_id, 'profile_avatar_image', true);
	$bio        = get_user_meta($auth_id,'description', true);

	$who_dis_is = true == $submission ? 'Image by' : 'Instructor';
	$auth_link  = sprintf('<a href="%s">%s</a>', cgc_get_profile_url( $auth_id ), get_the_author_meta('display_name', $auth_id) );

	ob_start();

		if ( $avatar ) {

			printf('<img src="%s" alt="%s">', $avatar, get_the_author_meta('display_name',$auth_id) );

		} else {

			echo get_avatar( $auth_id, 75 );
		}
		?>

		<p><?php echo $who_dis_is;?>: <?php echo $auth_link;?></p>

		<?php if (is_user_logged_in() && function_exists('cgc_follow_user') && $user_id != $auth_id) { ?>
			<div class="follow-links">
				<?php if(cgc_is_following($user_id, $auth_id)) { ?>
					<a href="#" class="unfollow following button" data-user-id="<?php echo $user_id; ?>" data-follow-id="<?php echo $auth_id; ?>"><i class="icon-ok"></i> Following</a>
				<?php } else { ?>
					<a href="#" class="follow not-following button" data-user-id="<?php echo $user_id; ?>" data-follow-id="<?php echo $auth_id; ?>"><icon class="icon-plus"></icon> Follow</a>
				<?php } ?>
			</div>
		<?php } ?>

		<?php if ( false == $submission ): ?>
			<p class="cgc-author-block--bio"><?php echo esc_html( wp_trim_words(20,'...',$bio ) );?></p>
			<a class="cgc-author-block--more" href="<?php echo get_author_posts_url( $auth_id ); ?>">More by this instructor</a>
		<?php endif;

	return ob_get_clean();
}

/**
*
*	Draws teh submission block shown in the submissions tab of a single exercise post type
*
*	@param $id int id of the post to pull author data from
* 	@return a formatted block of html with the submission and pass/fail actions
*/
function cgc_edu_submission_block( $id = 0 ) {

	if ( empty( $id ) )
		return;


	$status = cgc_edu_exercise_submission_status($id);

	if ( 'true' == $status ) {

		$class = 'passed';
		$label = 'Passed!';

	} elseif( 'false' == $status ) {

		$class = 'failed';
		$label = 'Did not pass';

	} elseif( 'open' == $status ) {

		$class = 'open';
		$label = 'Ready for Grading';

	}

	// get the type
	$type        = get_post_meta( get_the_ID(), '_cgc_edu_exercise_type', true);

	$cover = '';
	if ( 'video' == $type ) {

		// get the info about video
		$video  		= get_post_meta( $id, '_cgc_edu_exercise_video');
		$video_provider = $video ? $video[0]['provider'] : false;
		$video_url 		= $video ? trim($video[0]['url']) : false;


		$video_id      = cgc_get_video_id_from_string($video_provider,$video_url);

		$get_yt_cover  = 'youtube' == $video_provider ? sprintf('https://img.youtube.com/vi/%s/0.jpg',$video_id) : null;
		$yt_cover 		= $get_yt_cover ? sprintf('<div class="submission--cover" style="background-image:url(%s);"></div>',$get_yt_cover) : null;

		// vimeo
		$get_vim_cover = 'vimeo' == $video_provider ? unserialize(file_get_contents('http://vimeo.com/api/v2/video/'.$video_id.'.php')) : false;
		$vim_cover 		= $get_vim_cover ? sprintf('<div class="submission--cover" style="background-image:url(%s);"></div>',$get_vim_cover[0]['thumbnail_medium']) : null;

		if ( 'youtube' == $video_provider ) {

			$cover = $yt_cover;

		} elseif ( 'vimeo' == $video_provider ) {

			$cover = $vim_cover;

		} else {

			$cover = false;

		}

	} elseif ( 'image' == $type ) {

		$image 		= get_post_meta( $id, '_cgc_edu_exercise_image', true);
		$image      = $image ? wp_get_attachment_image_src($image,'medium') : 'http://placekitten.com/800/500';

		$cover 		= $image ? sprintf('<div class="submission--cover" style="background-image:url(\'%s\');"></div>',$image[0]) : null;

	} elseif ( 'sketchfab' == $type ) {

		$model  			= get_post_meta( $id , '_cgc_edu_exercise_sketchfab', true);
		$sketchfab_cover 	= cgc_edu_get_sketcfab_cover( $model );
		$cover 				= $sketchfab_cover ? sprintf('<div class="submission--cover" style="background-image:url(\'%s\');"></div>',$sketchfab_cover) : null;
	}

	?><li class="submission-status--<?php echo $class;?>">
		<a href="<?php echo get_permalink( $id );?>" data-title="<?php echo isset( $id->post_title ) ? esc_html( $id->post_title ) : false;?>">
			<span><?php echo $label;?></span>
			<?php echo $cover;?>
		</a>
	</li>
	<?php


}

/**
*
*	Find out if the video url they are submitting is vimeo or youtube
*
*/
function cgc_edu_video_provider( $source = ''){

	if ( empty( $source ) )
		return;

    if (strpos($source, 'youtube') > 0) {

        return 'youtube';

    } elseif (strpos($source, 'vimeo') > 0) {

        return 'vimeo';

    } else {

        return 'unknown';

    }
}


/**
*
*	Get the video ID from a string containing a youtube or vimeo video
*
*	@param $url string url of video
*	@param $provider string vimeo or youtube
*/
function cgc_get_video_id_from_string( $provider = '', $url = '') {

	// bail if no provider or url
	if ( empty( $provider ) || empty( $url ) )
		return;

	switch ($provider) {
		case 'youtube':
			$find = preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $matches);
			$id = $matches ? $matches[1] : null;
			break;
		case 'vimeo':
			$find = preg_match_all('%(?:player\.)?vimeo\.com(/video)?/(\d+)%i', $url, $matches);
			$id = $matches ? $matches[2][0] : null;
			break;
		default:
			$find = preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $matches);
			$id = $matches ? $matches[1] : null;
			break;
	}

	if ( $id )
		return $id;

}

/**
*
*	Return the thumbnail from the sketchfab api for the model being queried
*
*	@param $model string id of the sketchfab model
*	@return a thumbnail image
*	@todo this API call be need to be cached
*/
function cgc_edu_get_sketcfab_cover( $model = '' ) {

	if ( empty( $model ) )
		return;

    $apiurl = sprintf('https://sketchfab.com/oembed?url=https://sketchfab.com/models/%s', $model );

    $fetch = wp_remote_get($apiurl, array('sslverify'=>true));
    $remote = wp_remote_retrieve_body($fetch);

    if( !is_wp_error( $remote ) ) {
        $return = json_decode( $remote,true);
    }

    $out = $return['thumbnail_url'];

    if ( $out )
		return $out;
}


/**
*
* Modal displayed after grading
*
*
*/
function cgc_edu_grading_modal(){

	ob_start();

	?><div id="cgc-grading-modal" class="reveal-modal cgc-universal-modal">
		<div class="cgc-universal-modal--wrap">

			<h2 class="cgc-universal-modal--header">Thanks for Grading!</h2>
			<div class="cgc-universal-modal--body">
				<p>Our robots are calculating your grade into the collective. It is important you let the artist know why you did or did not pass their piece.</p>
				<p>The feedback will show publically underneath the piece in the discussion tab.</p>
				<p>Feedback or reasoning for your grade</p>
				<?php
					$comments_args = array(
					   	'label_submit'			=>'Send',
					    'title_reply'			=>'',
					    'comment_notes_after' 	=> '',
					    'logged_in_as'			=> '',
					   	'comment_field' 		=> '<p class="comment-form-comment"><textarea id="comment" name="comment" aria-required="true"></textarea></p>',
					);

					comment_form($comments_args, get_the_ID());
				?>
				<a class="button comment-cancel" href="#">No thanks</a>
			</div>

		</div>
	</div><?php

	return ob_get_clean();
}

/**
*
* Modal displayed to the user allowing them to submit an exercise
*
*
*/
function cgc_edu_exercise_submission_modal(){

	// type of exercise
	$type = get_post_meta( get_the_ID(), '_cgc_edu_exercise_type', true);

	ob_start();

	?><div id="cgc-exercise-submission-modal" class="reveal-modal cgc-universal-modal">
		<div class="cgc-universal-modal--wrap">

			<h2 class="cgc-universal-modal--header">Submit your exercise</h2>
			<div class="cgc-universal-modal--body">
				<p class="cgc-universal-modal--intro">CG Cookie is excited to work along side you in offering education to your class or team. Fill out the form below and a friendly cookie crew member will reach out and discuss how we can help.</p>

				<div id="cgc-edu-exercise--submission-results"></div>

				<div class="cgc-edu-upload--progress">
				    <div class="cgc-edu-upload--bar"></div >
				    <div class="cgc-edu-upload--percent">0%</div >
				</div>

				<?php if( 'image' == $type ) { ?>
					<script>
					jQuery(document).ready(function($){
					    $('#cgc-exercise-submit-form').submit(function(e){

					    	if ( $('#cgc-exercise-submit-form input[type="file"]').val() == '' ) {

					    		e.preventDefault();
					        	$('#cgc-edu-exercise--submission-results').text('Image required!');
					        	$('#cgc-edu-exercise--submission-results').addClass('error');
					        	return false;

					        }

					    });
					});
					</script>
				<?php } ?>

				<form id="cgc-exercise-submit-form" method="post" enctype="multipart/form-data">

					<label for="exercise-title">Title</label>
					<input class="exercise-field-required" type="text" name="exercise-title" value="" placeholder="My Awesome Submission">

					<?php switch ($type) {
						case 'image':
							?>
							<label class="file-upload" for="exercise-image">
								<i class="icon icon-upload"></i>
								<span>Upload Image</span>
								<span class="filename"></span>
								<input type="file" name="exercise-image" multiple="false">
							</label><?php
							break;
						case 'video':
							?>
							<label for="exercise-video">Video URL <i class="exercise-video-source icon "></i></label>
							<input class="exercise-field-required" id="exercise-video" type="text" name="exercise-video" value="">
							<input id="exercise-video-provider" type="hidden" name="exercise-video-provider" value=""><?php
							break;
						case 'sketchfab':
							?>
							<label for="exercise-sketchfab">Sketchfab Model ID</label>
							<input class="exercise-field-required" type="text" name="exercise-sketchfab" value=""><?php
							break;
						case 'unity':
							?>
							<label for="exercise-unity">Unity</label>
							<input class="exercise-field-required" type="text" name="exercise-unity" value=""><?php
							break;
						default:
							?>
							<label for="exercise-sketchfab">Sketchfab URL</label>
							<input class="exercise-field-required" type="text" name="exercise-sketchfab" value=""><?php
							break;
					}?>

					<label for="exercise-description">Description</label>
					<textarea class="exercise-field-required" id="exercise-description" form="cgc-exercise-submit-form" name="exercise-description" value="" placeholder="This is your chance to shine. Be very descriptive to encourage discussion and critiques."></textarea>

					<input type="hidden" name="action" value="process_submission">
					<input type="hidden" name="user_id" value="<?php echo get_current_user_ID(); ?>">
					<input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">
					<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('cgc-exercise-submission-nonce'); ?>"/>
					<div class="form-bottom">
						<input type="submit" value="Submit">
						<a class="button comment-cancel" href="#">Nah, nevermind</a>
						<p>By submitting your exercise, you agree to recieve public critiques, feedback, and voting on your image.</p>
					</div>
				</form>
			</div>

		</div>
	</div><?php

	return ob_get_clean();
}
/**
*
*	Calculates the number of passes on any given exercise submission and determins a pass or fail
*	This is only used to display the status of the vote to the user
*
*	@param $postid int id of the excersie submission to calcuate votes for
*	@param $passing int number required to pass or fail a submission
*/
function cgc_edu_exercise_grade( $postid = 0 ) {

	if ( empty( $postid ) )
		$postid = get_the_ID();

	$votes 			= get_post_meta( $postid, '_cgc_edu_exercise_vote', true );

	// get total votes
	$total_votes 	= 	get_post_meta( $postid, '_cgc_edu_exercise_total_votes', true );

	// get votes required to psas
	$connected      = get_post_meta( $postid, '_cgc_exercise_submission_linked_to', true);
	$passing     	= get_post_meta( $connected, '_cgc_edu_exercise_passing', true );

	// has this user voted
	$has_voted     = get_user_meta( get_current_user_ID(), '_cgc_edu_exercise-'.$postid.'_has_voted', true);

	if ( $total_votes >= $passing ) { // total points have reacehd teh total number required to pass

		if ( $votes >= $passing ) { // votes are greater than passing

			$return = 'This piece has passed the community vote! <span class="passed">Passed!</span>';

		} else {

			$return = 'This piece did not pass the community vote. <span class="failed">Did not pass</span>';
		}

	} else {

		if ( $has_voted ) {

			$return = 'Thanks for voting!';

		} elseif ( get_current_user_ID() == get_the_author_meta('ID') ) {

			$return = 'Your submission is still be voted on, hang tight!';

		} elseif( is_user_logged_in() ) {

			$return = 'Does the above image meet the exercise criteria?';

		} elseif( !is_user_logged_in() ) {

			$return = '<span class="cgc-edu-meta-login"><a href="#" data-reveal-id="header-login-form">Login</a> to grade this exercise!</span>';

		} else {

			$return = '';

		}

	}

	return $return;

}

/**
*
*
*	Count the number of votes for an exercise
*	@param $postid int id of exercise to check for votes on
*	@return the number of votes for the specific submission
*
*/
function cgc_edu_exercise_count_total_votes( $postid = 0 ) {

	if ( empty( $postid ) )
		$postid = get_the_ID();

	$votes = get_post_meta( $postid, '_cgc_edu_exercise_total_votes', true );

	return !empty( $votes ) ? $votes : '0';
}

/**
*
*
*	Return the status of an exercise submission
*	@param $postid int id of exercise submission
*	@return the status of the submission - Passed for passing, Failed for failing, or ready for grading if grading still open
*
*/
function cgc_edu_exercise_submission_status( $postid = '' ) {

	if ( empty( $postid ) )
		return;

	$votes 			= get_post_meta( $postid, '_cgc_edu_exercise_vote', true );

	// get total votes
	$total_votes 	= 	get_post_meta( $postid, '_cgc_edu_exercise_total_votes', true );

	// get votes required to psas
	$connected      = get_post_meta( $postid, '_cgc_exercise_submission_linked_to', true);
	$passing     	= get_post_meta( $connected, '_cgc_edu_exercise_passing', true );

	if ( $total_votes >= $passing ) { // total points have reacehd teh total number required to pass

		if ( $votes >= $passing ) { // votes are greater than passing

			return 'true';

		} else {

			return 'false';
		}

	} else {

		return 'open';
	}

}

/**
*
*	Return the id of the connected exercise
*
*	@param $postid int id of the submission
*	@return the id of the connected exercise from the exercise submission
*/
function cgc_edu_exercise_get_connected( $postid = 0 ) {

	if ( empty( $postid ) )
		$postid = get_the_ID();

	$connected = get_post_meta( $postid, '_cgc_exercise_submission_linked_to', true );

	return !empty( $connected ) ? $connected : false;
}

/**
*	Get a list of submissions linked to this exercise post
*
*	@param $id int id of the post to get the submissions for
*	@return array of postids
*/
function cgc_edu_exercise_get_submissions( $postid = ''){

	if ( empty( $postid ) )
		$postid = get_the_ID();

	$submissions = get_post_meta( $postid, '_cgc_exercise_submission_ids', true );

	return !empty($submissions) ? $submissions : false;

}

/**
*
*	Count the total number of submission that are published and not trashed
*
*	@param $postid int id of the exercise to count the submissions of
*	@return int total number of active submissions
*/
function cgc_edu_exercise_count_submissions( $postid = '' ) {

	if ( empty( $postid ) )
		$postid = get_the_ID();

	$submissions 	= cgc_edu_exercise_get_submissions( $postid );

	if ( empty( $submissions ) )
		return;

	$count = 0;

	foreach( (array) $submissions as $key => $id ) {

		$sub = get_post($id);

		if ( FALSE !== get_post_status( $id ) && 'publish' == get_post_status( $id ) ) {

			$count++;

		  	$return = $count;

		}
	}

	return $return;

}

/**
*
*	Add a submission postid to the array of ids for this exercise
*
*	@param $postid - int - id of the exercise to store the submissions to
*	@param $submission_id - int - the id of the submission being created
*
*/
function cgc_edu_exercise_log_submission( $postid = 0, $submission_id = 0 ) {

	if ( empty( $postid ) )
		return;

	// retrieve the IDs of all submissions for this exercise
	$submissions = cgc_edu_exercise_get_submissions( $postid );

	// go through the submissions check if its empty or an array
	if ( ! empty( $submissions ) && is_array( $submissions ) ) {
		$submissions[] = $submission_id;
	} else {
		$submissions = array();
		$submissions[] = $submission_id;
	}

	// create an array of submission_ids linked to this exercise
	update_post_meta( $postid, '_cgc_exercise_submission_ids', $submissions );

	// create a connection for this submission linked to the exercise
	update_post_meta( $submission_id, '_cgc_exercise_submission_linked_to', $postid );

}

/**
*
*	Get the available downloadable files for this exercise
*
*	@param $postid int id of the post to retrieve downloadable files for
*
*/
function cgc_edu_exercise_get_files( $postid = '' ) {

	if ( empty( $postid ) )
		$postid = get_the_ID();

	$files = get_post_meta( $postid, '_cgc_edu_exercise_files', true );

	return !empty($files) ? $files : false;
}
