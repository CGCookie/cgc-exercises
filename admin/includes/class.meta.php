<?php

class cgc_exercise_meta{

	public function __construct(){
		add_filter( 'cmb2_meta_boxes', array($this,'exercise_meta') );

	}

	public function exercise_meta( array $meta_boxes ) {

		$meta_boxes[] = array(
			'id'	=> '_exercise_setup',
			'title' => __('Exercise Setup', 'cgc-quiz'),
			'object_types' 	=> array('exercise'),
			'fields' => array(
				array(
					'id'			=> '_exercise_xp_worth',
					'name'			=> __('How much XP is this exercise worth?', 'cgc-exercises'),
					'type'			=> 'text'
				),
				array(
					'id'			=> '_exercise_votes_passing',
					'name'			=> __('How many votes are needed for this exercise to pass?', 'cgc-exercises'),
					'type'			=> 'text'
				)
			)
		);

		$meta_boxes[] = array(
			'id'	=> '_exercise_criteria_setup',
			'title' => __('Exercise Criteria', 'cgc-quiz'),
			'object_types' 	=> array('exercise'),
			'fields' => array(
				array(
					'id' 			=> '_exercise_criteria',
					'name' 			=> __('Exercise Criteria', 'cgc-quiz'),
					'type' 			=> 'textarea',
					'options'     => array(
						'group_title'   => __( 'Criterea {#}', 'cgc-quiz' ), // {#} gets replaced by row number
						'add_button'    => __( 'Add Another Criteria', 'cgc-quiz' ),
						'remove_button' => __( 'Remove Criteria', 'cgc-quiz' ),
						'sortable'      => true
					),
					'repeatable'     => true,
					'repeatable_max' => 20,
					'desc'			=> __('List the criteria fore this exercise.', 'cgc-quiz'),
				)
			)
		);

		return $meta_boxes;

	}

}
new cgc_exercise_meta;