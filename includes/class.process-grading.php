<?php

/**
*
*
*	Main class responsinsible for processing the grading of the submission
*
*/
class cgc_exercises_process_grading {

	function __construct(){

		//add_action( 'init', 		array($this,'process_grading') );
	}

	/**
	*
	*	Process the form submission
	*
	*/
	function process_grading(){

		check_ajax_referer('security','exercise_grading');

		die(); // ajax
	}
}
new cgc_exercises_process_grading;