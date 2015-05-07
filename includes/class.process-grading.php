<?php

/**
*
*
*	Main class responsinsible for processing the grading of the submission
*
*/
class cgc_exercises_process_grading {

	function __construct(){

		add_action( 'wp_ajax_process_grading', 				array($this, 'process_grading' ));
		add_action('cgc_edu_exercise_voted', 				array($this,'cgc_edu_exercise_voted'), 10, 3);
	}

	/**
	*
	*	Process the form submission
	*
	*	@todo - wp_mail isn't even firing
	*
	*/
	function process_grading(){

		$vote 			= isset( $_POST['vote'] ) ? $_POST['vote'] : null;
		$postid 		= isset( $_POST['post_id'] ) ? $_POST['post_id'] : null;
		$userid 		= get_current_user_ID();

		// get the yes votes
		$votes 			=	 get_post_meta( $postid, '_cgc_edu_exercise_vote', true );
		$total_votes 	= 	get_post_meta( $postid, '_cgc_edu_exercise_total_votes', true );

		if ( isset( $_POST['action'] ) && $_POST['action'] == 'process_grading' ) {

			// only run for logged in users
			if( !is_user_logged_in() || !current_user_can('edit_posts') )
				return;

			// ok security passes so let's process some data
			if ( wp_verify_nonce( $_POST['nonce'], 'cgc-exercise-nonce' ) ) {

				// first check to see if this user has voted
				$has_voted = get_user_meta( $userid, '_cgc_edu_exercise-'.$postid.'_has_voted', true);

				if ( $has_voted ) {

					echo 'Thanks for voting!';

				} elseif ( 'yes' == $vote ) { // user voted yes, so incremenet and set a flag for this user

					// increment
					update_post_meta( $postid, '_cgc_edu_exercise_vote', intval( $votes ) + 1 );

					// increment total overall votes
					update_post_meta( $postid, '_cgc_edu_exercise_total_votes', intval( $total_votes ) + 1 );

				} elseif ('no' == $vote) { // aww shcuks, they voted no, so subtract a point

					// decrement
					update_post_meta( $postid, '_cgc_edu_exercise_vote', intval( $votes ) - 1 );

					// decrement total overall votes
					update_post_meta( $postid, '_cgc_edu_exercise_total_votes', intval( $total_votes ) + 1 );

				}

				do_action('cgc_edu_exercise_voted', $postid, $userid, $vote );

			}

		}
		exit();
	}

	// award xp based on a pass or fail status
	function cgc_edu_exercise_voted( $postid, $userid, $vote ) {

		// get the yes votes
		$votes 			= get_post_meta( $postid, '_cgc_edu_exercise_vote', true );
		$total_votes 	= get_post_meta( $postid, '_cgc_edu_exercise_total_votes', true );
		$connected      = get_post_meta( $postid, '_cgc_exercise_submission_linked_to', true);

		// total votes required to pass
		$vote_allowed     	= get_post_meta( $connected, '_cgc_edu_exercise_passing', true );

		// get submission author
		$submission_author = get_post_field( 'post_author', $postid );
		$author_data 		= get_userdata( $submission_author );

		// get xp point value
		$xp_point_value   = get_post_meta( $connected,'_cgc_edu_exercise_xp_worth', true);

		/**
		*
		*	START LOGIC
		*
		*/

		// 1. set a flag for this user so they can't vote anymore
		update_user_meta( $userid, '_cgc_edu_exercise-'.$postid.'_has_voted', true );

		// 2. if the total # of votes is more than votes allowed
		if ( $total_votes >= $vote_allowed ) {

			// 3. if total yes votes are also more than votes allowed
			if ( $votes >= $vote_allowed ) {

				$args = array(
					'user_id'		=>	absint($submission_author),
					'xp_type'		=>  'exercise',
					'xp_date'		=>	current_time('timestamp'),
					'xp_amount'		=>	absint($xp_point_value),
					'last_page'		=> 	''
		   		);
		        cgc_increment_user_xp( $args );

		        // mail the user
				$message = "Hi ".$author_data->display_name.",\n";
				$message .= "Congrats on passing this exercise, certainly something to be excited and proud of! ".$xp_point_value." XP have been awarded!\n\n";
				$message .= "Great job!\n\n";
				$message .= "Best regards from the Crew at CG Cookie, Inc.";

				if ( !get_user_meta( $userid, 'no_emails', true ) ) {
					wp_mail( $author_data->user_email, 'Your Exercise Submission', $message );
				}


			// 4. this exercise did not pass so run our logic here
			} else {

				// mail the user
				$message = "Hi ".$author_data->display_name.",\n";
				$message .= "Unfortuately your exercise submission didn't pass and no XP was awarded.\n\n";
				$message .= "Better luck next time!\n\n";
				$message .= "Best regards from the Crew at CG Cookie, Inc.";

				if ( !get_user_meta( $userid, 'no_emails', true ) ) {
					wp_mail( $author_data->user_email, 'Your Exercise Submission', $message );
				}

			}

		}
	}

}
new cgc_exercises_process_grading;