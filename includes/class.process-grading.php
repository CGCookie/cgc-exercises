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
		add_action( 'wp_ajax_nopriv_process_grading', 		array($this, 'process_grading' ));
	}

	/**
	*
	*	Process the form submission
	*
	*	@todo - work in XP awarding
	*	@todo - work in total grading process logic
	*
	*/
	function process_grading(){

		$vote 			= isset( $_POST['vote'] ) ? $_POST['vote'] : null;
		$postid 		= isset( $_POST['post_id'] ) ? $_POST['post_id'] : null;
		$userid 		= isset( $_POST['user_id'] ) ? $_POST['user_id'] : null;

		// get the yes votes
		$votes 			=	 get_post_meta( $postid, '_cgc_edu_exercise_vote', true );
		$total_votes 	= 	get_post_meta( $postid, '_cgc_edu_exercise_total_votes', true );

		$connected      = get_post_meta( $postid, '_cgc_exercise_submission_linked_to', true);

		// total votes required to pass
		$vote_allowed     	= get_post_meta( $connected, '_cgc_edu_exercise_passing', true );

		// get submission author
		$submission_author = get_post_field( 'post_author', $connected );

		// get xp point value
		$xp_point_value   = get_post_meta( $connected,'_cgc_edu_exercise_xp_worth', true);

		if ( isset( $_POST['action'] ) && $_POST['action'] == 'process_grading' ) {

			// only run for logged in users
			if( !is_user_logged_in() )
				return;

			// ok security passes so let's process some data
			if ( wp_verify_nonce( $_POST['nonce'], 'cgc-exercise-nonce' ) ) {

				// first check to see if this user has voted
				$has_voted = get_user_meta( $userid, '_cgc_edu_exercise-'.$postid.'_has_voted', true);

				if ( $has_voted ) {

					echo 'Thanks for voting!';

				} elseif ( 'yes' == $vote ) { // user voted yes, so incremenet and set a flag for this user

					// and increment
					update_post_meta( $postid, '_cgc_edu_exercise_vote', intval( $votes ) + 1 );

					// increment total overall votes
					update_post_meta( $postid, '_cgc_edu_exercise_total_votes', intval( $total_votes ) + 1 );

				} elseif ('no' == $vote) { // aww shcuks, they voted no, so subtract a point

					// and increment
					update_post_meta( $postid, '_cgc_edu_exercise_vote', intval( $votes ) - 1 );

					// increment total overall votes
					update_post_meta( $postid, '_cgc_edu_exercise_total_votes', intval( $total_votes ) + 1 );

				}

				// set a flag for this user so they can't vote anymore
				update_user_meta( $userid, '_cgc_edu_exercise-'.$postid.'_has_voted', true );

				// the total # of votes has reached the total number of votes allowed, proceed with grading stuff
				if ( $total_votes && $votes >= $vote_allowed && function_exists('cgc_increment_user_xp') ) {

					$args = array(
						'user_id'		=>	$submission_author,
						'xp_type'		=>  'exercise',
						'xp_date'		=>	current_time('timestamp'),
						'xp_amount'		=>	$xp_point_value,
						'last_page'		=> 	''
			   		);
			        cgc_increment_user_xp( $args );


				}

			}

		}
		exit();
	}


}
new cgc_exercises_process_grading;