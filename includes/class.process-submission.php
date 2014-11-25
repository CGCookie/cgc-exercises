<?php

/**
*
*
*	Main class responsinsible for processing the front end submission
*
*/
class cgc_exercises_process_submission {

	function __construct(){

		add_action( 'wp_ajax_process_submission', 				array($this, 'process_submission' ));
		add_action( 'wp_ajax_nopriv_process_submission', 		array($this, 'process_submission' ));
	}

	/**
	*
	*	Process the form submission
	*
	*/
	function process_submission(){

		$postid 		= isset( $_POST['post_id'] ) ? $_POST['post_id'] : null;
		$userid 		= isset( $_POST['user_id'] ) ? $_POST['user_id'] : null;
		$title 			= isset( $_POST['exercise-title'] ) ? $_POST['exercise-title'] : null;
		$desc 			= isset( $_POST['exercise-description'] ) ? $_POST['exercise-description'] : null;


		if ( isset( $_POST['action'] ) && $_POST['action'] == 'process_submission' ) {

			// only run for logged in users
			if( !is_user_logged_in() )
				return;

			// ok security passes so let's process some data
			if ( wp_verify_nonce( $_POST['nonce'], 'cgc-exercise-submission-nonce' ) ) {

				// bail if we dont have rquired fields
				if ( empty( $title ) || empty( $desc ) ) {

					echo 'Title and description are required';

				} else {

					echo 'Thanks for your submission!';

				}

			}

		}

		exit(); // ajax
	}
}
new cgc_exercises_process_submission;