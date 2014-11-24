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

		echo 'righrt on';

		die(); // ajax
	}
}
new cgc_exercises_process_grading;