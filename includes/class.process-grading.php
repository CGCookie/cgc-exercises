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

		// get number of passes
		$votes_allowed 	= get_post_meta( $postid, '_cgc_edu_exercise_votes_allowed', true);
		$total_votes 	= cgc_edu_exercise_count_votes( $postid );

		$connected      = get_post_meta( $postid, '_cgc_exercise_submission_linked_to', true);
		$passing     	= get_post_meta( $connected, '_cgc_edu_exercise_passing', true );

		$thanks = 'Thanks for your vote! We are still awaiting more votes to calculate a pass or fail';

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

					echo $thanks;

					// get the old value
					$meta = get_post_meta( $postid, '_cgc_edu_exercise_vote', true );

					// and increment
					update_post_meta( $postid, '_cgc_edu_exercise_vote', intval( $meta ) + 1 );

				} else { // aww shcuks, they voted no, so let's gentlybail

					echo $thanks;

				}

				// set a flag for this user so they can't vote anymore
				update_user_meta( $userid, '_cgc_edu_exercise-'.$postid.'_has_voted', true );

				// total votes pass the threshold of allowed votes
				if ( $total_votes >= $passing ) {

					// award xp

					// send mail

				}

			}

		}
		exit();
	}

}
new cgc_exercises_process_grading;