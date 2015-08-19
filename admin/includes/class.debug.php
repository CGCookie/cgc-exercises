<?php

/**
*	Adds an admin menu page useful for looking up what submissions are attached to an exercise with ability to delete them
*	@since 5.3
*
*/
class cgcExerciseDebug {

	function __construct(){

		add_action( 'admin_menu', 							array($this,'submenu_page'));
		add_action('wp_ajax_process_debug_exercise', 		array($this,'process_exercise_debug') );
		add_action('wp_ajax_delete_exercise_submission', 	array($this,'delete_exercise_submission') );

	}

    // add submenu page
	function submenu_page() {
		add_submenu_page( 'edit.php?post_type=exercise', 'Debug', 'Debug', 'manage_options', 'exercise-debug', array($this,'submenu_page_callback') );
	}

	// submenu page callback
	function submenu_page_callback() {

		?>
		<h1>Exercise Debug</h1>
		<script>
			jQuery(document).ready(function($){

				$('#cgc-debug--exercise').on('submit',function(e) {

					e.preventDefault();

					var $this = $(this)
					, 	data = $this.serialize()

					$('#debug-response').children().unwrap();

					$.post( ajaxurl, data, function(response) {

						if ( response ) {

							$('#debug-response').html( response.data )

						}

					});

					$this.attr('data-connected', $('input[name="exercise_id"]').val() )

				});

				$(document).on('click', '#exercise-delete', function(e){

					e.preventDefault();

					var deleteData = {
						action:  'delete_exercise_submission',
						nonce: '<?php echo wp_create_nonce('delete-exercise-submission');?>',
						exercise_id: $(this).parent().attr('id'),
						connected: $('#cgc-debug--exercise').data('connected')
					},
					confirmed = confirm('Are you sure you want to delete this exercise submission?');

					console.log( deleteData )

				    if ( confirmed ) {

				      	$.post( ajaxurl, deleteData, function(response) {

				      		console.log(response)

							alert('Deleted!');
							//location.reload();

						});
				    }
				});

			});
		</script>
		<form id="cgc-debug--exercise" method="post" enctype="multipart/form-data">
			<label>Enter an exercise post id to retrieve the submissions associated with the exercise.
				<input type="text" value="" name="exercise_id">
			</label>
			<input type="submit" value="Submit">
			<input type="hidden" name="action" value="process_debug_exercise">
			<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('process-debug-exercise');?>">
		</form>
		<ul id="debug-response"></ul>
		<?php

	}

	function process_exercise_debug(){

		if ( isset( $_POST['action'] ) && $_POST['action'] == 'process_debug_exercise' ) {

			if ( wp_verify_nonce( $_POST['nonce'], 'process-debug-exercise' ) ) {

				// bail if we're not an admin
				if ( !current_user_can('manage_options') ) {
					wp_send_json_error();
				}

				$id  	= isset($_POST['exercise_id']) ? $_POST['exercise_id'] : false;

				$submissions = get_post_meta( absint( $id ), '_cgc_exercise_submission_ids', true );

				if ( !empty( $submissions ) ) {

					$payload = '';
					foreach ( $submissions as $submission ) {

						$connected = get_post_meta( $submission, '_cgc_exercise_submission_linked_to', true );

						$payload .= sprintf('
							<li id="%s" class="exercise-item">
								<a href="%s">%s</a>
								<span style="margin-left:5px;cursor:pointer" id="exercise-delete"><i class="dashicons dashicons-trash"></i></span>
							</li>',
							$submission, get_permalink( $submission ), get_the_title( $submission ) );
					}

					wp_send_json_success( $payload );

				} else {

					wp_send_json_success( array('payload' => 'none-found' ) );
				}
			}

		}

	}

	function delete_exercise_submission() {

		if ( isset( $_POST['action'] ) && $_POST['action'] == 'delete_exercise_submission' ) {

			if ( wp_verify_nonce( $_POST['nonce'], 'delete-exercise-submission' ) ) {

				// bail if we're not an admin
				if ( !current_user_can('manage_options') ) {
					wp_send_json_error();
				}

				$exercise_id = isset( $_POST['exercise_id'] ) ? absint( $_POST['exercise_id'] ) : false;
				$connected     = isset( $_POST['connected'] ) ? absint( $_POST['connected'] ) : false;

				self::delete_submission_from_exercise( $exercise_id, $connected );

				wp_delete_post( $exercise_id );

				wp_send_json_success( array('payload' => $exercise_id, 'connected' => $connected ) );

			}

		}
	}

	/**
	*
	*	Find the submission within the post meta array and delete it
	*	@since 5.3
	*/
	static function delete_submission_from_exercise( $exercise_id, $connected ) {

		if ( empty( $exercise_id ) )
			return;

		$submissions = get_post_meta( absint( $connected ), '_cgc_exercise_submission_ids', true );

		$modified = false;

		if ( !empty( $submissions ) ) {

			foreach ( $submissions as $key => $submission ) {

				if ( $submission == $exercise_id ) {

					unset( $submissions[$key] );

					$modified = true;
				}
			}

		}

		if ( $modified ) {
			return true;
		} else {
			return false;
		}

	}

}
new cgcExerciseDebug;