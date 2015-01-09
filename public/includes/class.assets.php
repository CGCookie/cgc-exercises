<?php

class cgc_exercises_assets {

	function __construct(){
		add_action('wp_enqueue_scripts', array($this,'scripts'));
	}

	function scripts(){

	    if ( 'exercise' == get_post_type() || 'exercise_submission' == get_post_type() ):

	    	wp_enqueue_script('jquery-form');
			wp_enqueue_style('cgc-exercises-style', CGC_EXERCISES_URL.'/public/assets/css/style.css', CGC_EXERCISES_VERSION, true);
			wp_enqueue_script('cgc-exercises', CGC_EXERCISES_URL.'/public/assets/js/cgc-exercises.js', array('jquery'), CGC_EXERCISES_VERSION, true);

			wp_localize_script('cgc-exercises', 'cgc_exercise_meta', array(
				'ajaxurl' 		=> admin_url( 'admin-ajax.php' ),
				'nonce'			=> wp_create_nonce('cgc-exercise-nonce'),
				'shareExercise' => cgc_exercises_share_exercise()
			));

		endif;
	}
}
new cgc_exercises_assets;