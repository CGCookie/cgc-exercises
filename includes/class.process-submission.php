<?php

/**
*
*
*	Main class responsinsible for processing the front end submission
*
*/
class cgc_exercises_process_submission {

	function __construct(){

		add_action( 'wp_ajax_process_grading', 				array($this, 'process_submission' ));
		add_action( 'wp_ajax_nopriv_process_grading', 		array($this, 'process_submission' ));
	}

	/**
	*
	*	Process the form submission
	*
	*/
	function process_submission(){

		check_ajax_referer('cgc-exercise-nonce','nonce');

		echo 'ajax scucess';

		die(); // ajax
	}
}
new cgc_exercises_process_submission;