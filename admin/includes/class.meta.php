<?php

class cgc_exercise_meta{

	public function __construct(){
		add_filter( 'cmb2_meta_boxes', array($this,'exercise_meta') );
		add_filter( 'cmb2_init', array($this,'exercise_submission_meta') );
	}

	public function exercise_submission_meta() {

	    $cmb = new_cmb2_box( array(
	        'id'            => 'submission_data',
	        'title'         => __( 'Submission Data', 'cmb2' ),
	        'object_types'  => array( 'exercise_submission' ),
	        'context'       => 'normal',
	        'priority'      => 'low',
	        'show_names'    => true,
	    ) );

	    $cmb->add_field( array(
	        'name'       => 'Sketchfab URL',
	        'desc'       => 'Sketchfab URL',
	        'id'         => '_cgc_edu_exercise_sketchfab',
	        'type'       => 'text'
	    ) );


	    $cmb->add_field( array(
	        'name'       => 'Video URL',
	        'desc'       => 'Video URL',
	        'id'         => '_cgc_edu_exercise_video',
	        'type'       => 'text'
	    ) );
	}

	public function exercise_meta( array $meta_boxes ) {

		$meta_boxes[] = array(
			'id'	=> '_exercise_setup',
			'title' => __('Exercise Setup', 'cgc-exercises'),
			'object_types' 	=> array('exercise'),
			'context'		=> 'side',
			'priority'		=> 'low',
			'fields' => array(
				array(
					'id'			=> '_cgc_edu_exercise_xp_worth',
					'name'			=> __('How much XP is this exercise worth?', 'cgc-exercises'),
					'type'			=> 'text_small'
				),
				array(
					'id'			=> '_cgc_edu_exercise_passing',
					'name'			=> __('How many votes are needed for this exercise to pass?', 'cgc-exercises'),
					'type'			=> 'text_small'
				),
				array(
					'id'			=> '_cgc_edu_exercise_vote_threshold',
					'name'			=> 'How many votes are allowed?',
					'type'			=> 'text_small',
					'default'		=> 10
				),
				array(
					'id'			=> '_cgc_edu_exercise_type',
					'name'			=> __('Which submission type is accepted for this exercise?', 'cgc-exercises'),
					'type'			=> 'select',
					'default'		=> 'image',
					'options'		=> array(
						'image'		=> __('Image','cgc-exercises'),
						'video'		=> __('Video','cgc-exercises'),
						'sketchfab'	=> __('Sketchfab','cgc-exercises'),
						'unity'		=> __('Unity','cgc-exercises')
					)
				)
			)
		);

		$meta_boxes[] = array(
			'id'	=> '_exercise_video',
			'title' => __('Exercise Video (optional)', 'cgc-exercises'),
			'object_types' 	=> array('exercise'),
			'context'		=> 'side',
			'priority'		=> 'low',
			'fields' => array(
				array(
					'id'			=> '_exercise_video',
					'name'			=> '',
					'desc'			=> 'Enter the Wistia Video ID for this exercise if applicable',
					'type'			=> 'text_small'
				)
			)
		);

		$meta_boxes[] = array(
			'id'	=> '_exercise_library',
			'title' => __('Display in Library', 'cgc-exercises'),
			'object_types' 	=> array('exercise'),
			'context'		=> 'side',
			'priority'		=> 'low',
			'fields' => array(
				array(
					'id'			=> '_exercise_library_display',
					'name'			=> '',
					'desc'			=> 'Check this box to show this exercise in the Library.',
					'type'			=> 'checkbox'
				)
			)
		);

		$meta_boxes[] = array(
			'id'	=> '_exercise_criteria_setup',
			'title' => __('Exercise Criteria', 'cgc-exercises'),
			'object_types' 	=> array('exercise'),
			'fields' => array(
				array(
					'id' 			=> '_cgc_edu_exercise_criteria',
					'name' 			=> __('Exercise Criteria', 'cgc-exercises'),
					'type' 			=> 'textarea_small',
					'options'     => array(
						'group_title'   => __( 'Criterea {#}', 'cgc-exercises' ), // {#} gets replaced by row number
						'add_button'    => __( 'Add Another Criteria', 'cgc-exercises' ),
						'remove_button' => __( 'Remove Criteria', 'cgc-exercises' ),
						'sortable'      => true
					),
					'repeatable'     => true,
					'repeatable_max' => 20,
					'desc'			=> __('List the criteria fore this exercise.', 'cgc-exercises'),
				)
			)
		);

		$meta_boxes[] = array(
			'id'	=> '_exercise_files_setup',
			'title' => __('Exercise Files', 'cgc-exercises'),
			'object_types' 	=> array('exercise'),
			'fields' => array(
				array(
					'id'		=> '_cgc_edu_exercise_files_desc',
					'name'		=> 'Description to be used for the Project Files area',
					'type'		=> 'wysiwyg',
					'options' => array(
				        'wpautop' => true, // use wpautop?
				        'media_buttons' => false, // show insert/upload button(s)
				        'textarea_rows' => get_option('default_post_edit_rows', 5)
				    ),
					'desc'		=> 'Add a description to be shown in the Project Files tab'
				),
				array(
					'id' 			=> '_cgc_edu_exercise_files',
					'name' 			=> __('Exercise Files', 'cgc-exercises'),
					'type' 			=> 'group',
					'repeatable'     => true,
					'repeatable_max' => 20,
					'options'     => array(
						'group_title'   => __( 'File {#}', 'cgc-exercises' ), // {#} gets replaced by row number
						'add_button'    => __( 'Add Another File', 'cgc-exercises' ),
						'remove_button' => __( 'Remove File', 'cgc-exercises' ),
						'sortable'      => true
					),
					'fields'		=> array(
						array(
							'id' 			=> '_file',
							'name' 			=> __('File', 'cgc-exercises'),
							'type' 			=> 'text',
							'desc'			=> __('Add any downloadable file URLs here.', 'cgc-exercises'),
						),
						array(
							'id' 			=> '_size',
							'name' 			=> __('File Size', 'cgc-exercises'),
							'type' 			=> 'text_small',
							'desc'			=> __('File size of video', 'cgc-exercises'),
						),
						array(
							'id' 			=> '_title',
							'name' 			=> __('Title', 'cgc-exercises'),
							'type' 			=> 'text',
							'desc'			=> __('Add any downloadable file URLs here.', 'cgc-exercises'),
						)
					)
				)
			)
		);
		return $meta_boxes;

	}

}
new cgc_exercise_meta;