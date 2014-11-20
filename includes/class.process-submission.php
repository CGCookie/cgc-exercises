<?php

/**
*
*
*	Main class responsinsible for processing the front end submission
*
*/
class cgc_exercises_process_submission {

	function __construct(){

		//add_action('init', array($this,'process_submission'));
	}

	/**
	*
	*	Process the form submission
	*
	*/
	function process_submission(){

		check_ajax_referer('security','exercise_submission');

		die(); // ajax
	}
}
new cgc_exercises_process_submission;