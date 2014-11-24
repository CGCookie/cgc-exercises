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


		if ( isset( $_POST['action'] ) && $_POST['action'] == 'process_grading' ) {

			if( !is_user_logged_in() )
				return;

			if ( wp_verify_nonce( $_POST['nonce'], 'cgc-exercise-nonce' ) ) {

				if ( 'yes' == $vote ) {

					// the vote is yes so let's save some post meta to this submission
					// need to set a flag for this user that they've already voted on this

					echo 'you voted yes';

					// get the old value
					$meta = get_post_meta( $postid, '_cgc_edu_exercise_vote', true );

					// and increment
					update_post_meta( $postid, '_cgc_edu_exercise_vote', intval( $meta ) + 1 );


				} else {

					// ok the user has voted no, so let's just return that message and bail out of the function
					echo 'you voted no';
				}

			}

		}

		die(); // ajax
	}

	function increment_vote( $postid = 0, $value = '' ){

		$meta = get_post_meta( $postid, '_cgc_edu_exercise_vote', $value );

		update_post_meta( $postid, '_cgc_edu_exercise_vote', intval( $meta ) + 1 );

		return $meta;

	}
}
new cgc_exercises_process_grading;