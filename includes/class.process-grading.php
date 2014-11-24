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
	*/
	function process_grading(){

		$vote = isset( $_POST['vote'] ) ? $_POST['vote'] : null;
		$postid = isset( $_POST['post_id'] ) ? $_POST['post_id'] : null;
		$userid = isset( $_POST['user_id'] ) ? $_POST['user_id'] : null;

		if ( isset( $_POST['action'] ) && $_POST['action'] == 'process_grading' ) {

			// only run for logged in users
			if( !is_user_logged_in() )
				return;

			// ok security passes so let's process some data
			if ( wp_verify_nonce( $_POST['nonce'], 'cgc-exercise-nonce' ) ) {

				// first check to see if this user has voted
				$has_voted = get_user_meta( $userid, '_cgc_edu_exercise-'.$postid.'_has_voted', true);

				if ( $has_voted ) {
					echo 'You already voted yo!';
				} else {
					// user voted yes, so incremenet and set a flag for this user
					if ( 'yes' == $vote ) {

						// the vote is yes so let's save some post meta to this submission

						echo 'you voted yes';

						// get the old value
						$meta = get_post_meta( $postid, '_cgc_edu_exercise_vote', true );

						// and increment
						update_post_meta( $postid, '_cgc_edu_exercise_vote', intval( $meta ) + 1 );

						// set a flag for this user so they can't vote anymore
						update_user_meta( $userid, '_cgc_edu_exercise-'.$postid.'_has_voted', true );

					// aww shcuks, they voted no, so let's gentlybail
					} else {

						echo 'you voted no';
					}
				}

			}

		}
		exit();
	}

}
new cgc_exercises_process_grading;