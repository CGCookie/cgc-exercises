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

		check_ajax_referer('cgc-exercise-nonce','nonce');

		$value = isset( $_POST['fields'] ) ? $_POST['fields'] : null;

		if ( 'vote=yes' == $value ) {

			// the vote is yes so let's save some post meta to this submission
			// need a way to check for previous post meta so this likely needs to be a function

			// need to set a flag for this user that they've already voted on this
			echo 'you voted yes';

		} else {

			// ok the user has voted no, so let's just return that message and bail out of the function
			echo 'you voted no';
		}

		die(); // ajax
	}
}
new cgc_exercises_process_grading;