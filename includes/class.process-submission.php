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

		// types
		$image			= isset( $_POST['exercise-image']) ? $_POST['exercise-image'] : null;
		$sketchfab		= isset( $_POST['exercise-sketchfab']) ? $_POST['exercise-sketchfab'] : null;
		$unity			= isset( $_POST['exercise-unity']) ? $_POST['exercise-unity'] : null;
		$video			= isset( $_POST['exercise-video']) ? $_POST['exercise-video'] : null;


		if ( isset( $_POST['action'] ) && $_POST['action'] == 'process_submission' ) {

			// only run for logged in users
			if( !is_user_logged_in() )
				return;

			// ok security passes so let's process some data
			if ( wp_verify_nonce( $_POST['nonce'], 'cgc-exercise-submission-nonce' ) ) {

				// bail if we dont have rquired fields
				if ( empty( $title ) || empty( $desc ) ) {

					echo '<div class="error">Whoopsy! Looks like you forgot the Title and/or description.</div>';

				} else {

					// create an exercise submission
					$post_args = array(
					  'post_title'    => $title,
					  'post_content'  => $desc,
					  'post_status'   => 'publish',
					  'post_type'	  => 'exercise_submission',
					  'post_author'   => $userid
					);
					$submission_id = wp_insert_post( $post_args );

					// do the saving of submission ids and such 
					cgc_edu_exercise_log_submission( $postid, $submission_id );

					// save misc fields
					if ( $image ) {
						update_post_meta( $postid, '_cgc_edu_exercise_image', sanitize_text_field( $image ) );
					}
					if ( $sketchfab ) {
						update_post_meta( $postid, '_cgc_edu_exercise_sketchfab', sanitize_text_field( $sketchfab ) );
					}
					if ( $unity ) {
						update_post_meta( $postid, '_cgc_edu_exercise_unity', sanitize_text_field( $unity ) );
					}
					if ( $video ) {
						update_post_meta( $postid, '_cgc_edu_exercise_video', sanitize_text_field( $video ) );
					}

					// @todo - display a new thanks modal in place of this
					echo '<div class="success">Success! You can view your submission <a href="'.get_permalink($submission_id).'">here</a></div>';

				}

			}

		}

		exit(); // ajax
	}
}
new cgc_exercises_process_submission;