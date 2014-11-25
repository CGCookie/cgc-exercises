<?php


class cgc_Exercises_Type {

	public function __construct(){

       	add_action('init',array($this,'do_exercise_type'));
       	add_action('init',array($this,'do_exercise_submission_type'));
	}
	/**
	 	* Creates an exercise post type
	 	*
	 	* @since    1.0.0
	*/
	function do_exercise_type() {

		$labels = array(
			'name'                		=> _x( 'Exercises','cgc-exercises' ),
			'singular_name'       		=> _x( 'Exercise','cgc-exercises' ),
			'menu_name'           		=> __( 'Exercises', 'cgc-exercises' ),
			'parent_item_colon'   		=> __( 'Parent Exercise:', 'cgc-exercises' ),
			'all_items'           		=> __( 'All Exercises', 'cgc-exercises' ),
			'view_item'           		=> __( 'View Exercise', 'cgc-exercises' ),
			'add_new_item'        		=> __( 'Add New Exercise', 'cgc-exercises' ),
			'add_new'             		=> __( 'New Exercise', 'cgc-exercises' ),
			'edit_item'           		=> __( 'Edit Exercise', 'cgc-exercises' ),
			'update_item'         		=> __( 'Update Exercise', 'cgc-exercises' ),
			'search_items'        		=> __( 'Search Exercises', 'cgc-exercises' ),
			'not_found'           		=> __( 'No Exercises found', 'cgc-exercises' ),
			'not_found_in_trash'  		=> __( 'No Exercises found in Trash', 'cgc-exercises' ),
		);
		$args = array(
			'label'               		=> __( 'Exercises', 'cgc-exercises' ),
			'description'         		=> __( 'Create exercises', 'cgc-exercises' ),
			'labels'              		=> $labels,
			'supports'            		=> array( 'editor','title', 'comments'),
			'public'              		=> true,
 			'show_ui' 					=> true,
			'query_var' 				=> true,
			'can_export' 				=> true,
			'has_archive'				=> 'exercises',
			'rewrite'					=> array('with_front' => false, 'slug' => 'exercise'),
			'capability_type' 			=> 'post'
		);

		register_post_type( 'exercise',$args );

	}

	function do_exercise_submission_type(){

		$labels = array(
			'name'                		=> _x( 'Exercise Submissions','cgc-exercises' ),
			'singular_name'       		=> _x( 'Exercise Submission','cgc-exercises' ),
			'menu_name'           		=> __( 'Exercise Submissions', 'cgc-exercises' ),
			'parent_item_colon'   		=> __( 'Parent Exercise Submission:', 'cgc-exercises' ),
			'all_items'           		=> __( 'All Exercise Submissions', 'cgc-exercises' ),
			'view_item'           		=> __( 'View Exercise Submission', 'cgc-exercises' ),
			'add_new_item'        		=> __( 'Add New Exercise Submission', 'cgc-exercises' ),
			'add_new'             		=> __( 'New Exercise Submission', 'cgc-exercises' ),
			'edit_item'           		=> __( 'Edit Exercise Submission', 'cgc-exercises' ),
			'update_item'         		=> __( 'Update Exercise Submission', 'cgc-exercises' ),
			'search_items'        		=> __( 'Search Exercise ubmissions', 'cgc-exercises' ),
			'not_found'           		=> __( 'No Exercise Submissions found', 'cgc-exercises' ),
			'not_found_in_trash'  		=> __( 'No Exercise Submissions found in Trash', 'cgc-exercises' ),
		);
		$args = array(
			'label'               		=> __( 'Exercise Submissions', 'cgc-exercises' ),
			'description'         		=> __( 'Create exercise submissions', 'cgc-exercises' ),
			'labels'              		=> $labels,
			'supports'            		=> array( 'editor','title', 'comments'),
			'public'              		=> true,
 			'show_ui' 					=> true,
			'query_var' 				=> true,
			'can_export' 				=> true,
			'has_archive'				=> 'exercise-submissions',
			'rewrite'					=> array('with_front' => false, 'slug' => 'exercise-submission'),
			'capability_type' 			=> 'post'
		);

		register_post_type( 'exercise_submission',$args );
	}
}

new cgc_Exercises_Type;