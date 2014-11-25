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
*	Calculates the number of passes on any given exercise submission and determins a pass or fail
*
*	@param $postid int id of the excersie submission to calcuate votes for
*	@param $passing int number required to pass or fail a submission
*/
function cgc_edu_exercise_grade( $postid = 0 ) {

	$votes_allowed 	= get_post_meta( $postid, '_cgc_edu_exercise_votes_allowed', true);
	$total_votes 	= get_post_meta( $postid, '_cgc_edu_exercise_vote', true );
	$passing     	= get_post_meta( $postid, '_cgc_edu_exercise_passing', true );
	$thanks 		= 'Thanks for your vote! We are still awaiting more votes to calculate a pass or fail';

	if ( $total_votes >= $votes_allowed ) {

		$return = $thanks;

		if ( $total_votes >= $passing ) {

			$return = 'passed';

		} else {

			$return = 'failed';
		}

	} else {

		$return = 'Does the above image meet the exercise criteria?';
	
	}

	return $return;
}








