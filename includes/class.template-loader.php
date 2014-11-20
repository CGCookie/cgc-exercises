<?php

class cgc_exercise_template_loader {

	function __construct() {

		add_filter( 'template_include', array($this,'template_loader'));

	}

	/**
	*
	* @since version 1.0
	* @param $template - return based on view
	* @return page template based on view
	*/
	function template_loader($template) {

	    // override single
	    if ( 'exercise' == get_post_type() ):

			$template = CGC_EXERCISES_DIR.'templates/single-exercise.php';

	    endif;

	   	// override single
	    if ( 'exercise_submission' == get_post_type() ):

			$template = CGC_EXERCISES_DIR.'templates/single-exercise_submission.php';

	    endif;

	    return $template;

	}

}
new cgc_exercise_template_loader;