<?php

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

	$who_dis_is = true == $submission ? 'Image by' : 'Instructor';
	$auth_link  = sprintf('<a href="%s">%s</a>', cgc_get_profile_url( $auth_id ), get_the_author_meta('display_name', $auth_id) );

	ob_start();

		if ( $avatar ) {

			printf('<img src="%s" alt="%s">', $avatar, the_author_meta('display_name',$auth_id) );

		} else {

			echo get_avatar( $auth_id, 80 );
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
			<p>Big bio</p>
			<a href="#">More by this instructor</a>
		<?php endif;

	return ob_get_clean();
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
* Modal displayed after grading
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

				<form id="cgc-exercise-submit-form" method="post" enctype="multipart/form-data">

					<label for="exercise-title">Title</label>
					<input type="text" name="exercise-title" value="" placeholder="My Awesome Submission">

					<?php switch ($type) {
						case 'image':
							?>
							<label class="file-upload" for="exercise-image">
								<i class="icon icon-upload"></i>
								Upload Image
								<input type="file" name="exercise-image" multiple="false">
							</label><?php
							break;
						case 'video':
							?>
							<label for="exercise-video">Video URL</label>
							<input type="text" name="exercise-video" value=""><?php
							break;
						case 'sketchfab':
							?>
							<label for="exercise-sketchfab">Sketchfab URl</label>
							<input type="text" name="exercise-sketchfab" value=""><?php
							break;
						case 'unity':
							?>
							<label for="exercise-unity">Unity</label>
							<input type="text" name="exercise-unity" value=""><?php
							break;
						default:
							?>
							<label for="exercise-sketchfab">Sketchfab URL</label>
							<input type="text" name="exercise-sketchfab" value=""><?php
							break;
					}?>

					<label for="exercise-description">Description</label>
					<textarea form="cgc-exercise-submit-form" name="exercise-description" value="" placeholder="This is your chance to shine. Be very descriptive to encourage discussion and critiques."></textarea>

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
		} else {
			$return = 'Does the above image meet the exercise criteria?';
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






