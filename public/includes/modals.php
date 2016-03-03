<?php
/**
*
* Modal displayed to the user allowing them to submit an exercise
*
*
*/
add_action('wp_footer','cgc_edu_exercise_submission_modal');
function cgc_edu_exercise_submission_modal(){


	if ( 'exercise' == get_post_type() ):

		// type of exercise
		$type = get_post_meta( get_the_ID(), '_cgc_edu_exercise_type', true);

		?>
		<!-- Exercise Submission Modal -->
		<div class="modal fade modal--exercise-submission" id="modal--exercise-submission" tabindex="-1" role="dialog">
		    <div class="modal-dialog">
			  	<div class="modal-content">

			  		<div class="modal-header">
						<h2 class="modal-title">Submit your exercise</h2>
					</div>
					<div class="modal-body">

						<p class="modal--intro">You are submitting your work to be graded by the community here at CG Cookie. This is a huge step for any artist while gaining the experience, challenge and critiques of our peers.</p>

						<div id="cgc-edu-exercise--submission-results"></div>

						<div class="media-upload--progress not-visible"><div class="media-upload--bar"></div ><div class="media-upload--percent">0%</div ></div>

						<form id="cgc-exercise-submit-form" method="post" enctype="multipart/form-data">

							<p>
								<input required type="text" name="exercise-title" value="" placeholder="My Awesome Submission">
							</p>

							<?php switch ($type) {
								case 'image':
									?>
									<fieldset class="media-submission--upload">
										<label class="file-upload" for="exercise-image">
											<i class="icon icon-upload"></i>
											<span>Upload Image</span>
											<span class="filename">Recommended size - 800 x 600</span>
											<input required type="file" name="exercise-image" multiple="false">
										</label>
									</fieldset>
									<?php
									break;
								case 'video':
									?>
									<label for="exercise-video">Video URL <i class="exercise-video-source icon "></i></label>
									<p><input id="exercise-video" type="text" name="exercise-video" value=""></p>
									<input required id="exercise-video-provider" type="hidden" name="exercise-video-provider" value=""><?php
									break;
								case 'sketchfab':
									?>
									<label for="exercise-sketchfab">Sketchfab Model URL</label>
									<p><input required type="text" name="exercise-sketchfab" value="" placeholder="https://sketchfab.com...."></p><?php
									break;
								case 'unity':
									?>
									<label for="exercise-unity">Link to Unity HTML File <small>Upload your Unity Files onto the web (Dropbox works great for this), then paste the link to the HTML file here.</small></label>
									<p><input type="text" name="exercise-unity" value=""></p>
									<label class="file-upload" for="exercise-image">
										<i class="icon icon-upload"></i>
										<span>Screenshot</span>
										<span class="filename">Recommended size - 900 x 500</span>
										<input required type="file" name="exercise-image" multiple="false">
									</label>
									<?php
									break;
								default:
									?>
									<label for="exercise-sketchfab">Sketchfab URL</label>
									<input required type="text" name="exercise-sketchfab" value=""><?php
									break;
							}?>

							<p>
								<textarea required id="exercise-description" form="cgc-exercise-submit-form" name="exercise-description" value="" placeholder="This is your chance to shine. Be very descriptive to encourage discussion and critiques."></textarea>
							</p>

							<div class="form-bottom">
								<input type="hidden" name="action" value="process_submission">
								<input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">
								<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('cgc-exercise-submission-nonce'); ?>"/>
								<input type="submit" value="Submit">
								<a class="button comment-cancel" href="#">Nah, nevermind</a>
								<p>By submitting your exercise, you agree to recieve public critiques, feedback, and voting on your image.</p>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	<?php endif;

}


/**
*
* Modal displayed after grading
*
*
*/
add_action('wp_footer','cgc_edu_grading_modal');
function cgc_edu_grading_modal(){

	if ( 'exercise_submission' == get_post_type() ):
	?>
	<div class="modal fade modal--exercise-submission" id="cgc-grading-modal" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<h2 class="modal-header">Thanks for Grading!</h2>
				<div class="modal-body">
					<p>Our robots are calculating your grade into the collective. It is important you let the artist know why you did or did not pass their piece.</p>
					<p>The feedback will show publically underneath the piece in the discussion tab.</p>
					<p>Feedback or reasoning for your grade</p>
					<?php
						$comments_args = array(
						   	'label_submit'			=>'Send',
						    'title_reply'			=>'',
						    'comment_notes_after' 	=> '',
						    'logged_in_as'			=> '',
						   	'comment_field' 		=> '<p class="comment-form-comment"><textarea id="comment" name="comment" aria-required="true"></textarea></p>',
						);

						comment_form($comments_args, get_the_ID());
					?>
					<a class="button comment-cancel" href="#">No thanks</a>
				</div>
			</div>
		</div>
	</div><?php endif;
}


/**
*
* Modal displayed after grading
*
*	@param $player_url string the url to the users submittd unity html file
*/
add_action('wp_footer','cgc_edu_unity_modal');
function cgc_edu_unity_modal(){

	if ( 'exercise_submission' == get_post_type() ):

		$player_url 	= get_post_meta( get_the_ID(), '_cgc_edu_exercise_unity', true);
	 	$ssl_safe_url 	= $player_url ? cgc_safe_ssl_url( $player_url ) : false;

		?>
		<div class="modal fade modal--exercise-submission" id="cgc-unity-modal" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<iframe width="100%" height="500px" src="<?php echo esc_url( $ssl_safe_url );?>" frameborder="0" allowfullscreen mozallowfullscreen="true" webkitallowfullscreen="true" onmousewheel=""></iframe>
					</div>
				</div>
			</div>
		</div>
	<?php endif;
}