<?php get_header(); ?>
	<div class="page-content">
		<div class="page-content-inner">
			<div id="main" class="main-content no-sidebar">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post();

						$connected      = get_post_meta( get_the_ID(), '_cgc_exercise_submission_linked_to', true);
						$criteria     	= get_post_meta( $connected, '_cgc_edu_exercise_criteria', true );

						// submitted image
						$image 			= get_post_meta( get_the_ID(), '_cgc_edu_exercise_image', true);
						$image          = $image ? wp_get_attachment_image_src($image,'full') : 'http://placekitten.com/800/500';

						// connected lesson
						$lesson_id     = get_post_meta( get_the_ID(),'_cgc_exercise_submission_linked_to', true);
						$lesson_link = $lesson_title = '';

						// submission type
						$type        = get_post_meta( $connected, '_cgc_edu_exercise_type', true);

						$video  		= get_post_meta( get_the_ID(), '_cgc_edu_exercise_video', true);

						$type_sketchfab  	= get_post_meta( get_the_ID(), '_cgc_edu_exercise_sketchfab', true);


						// tally some votes
						$total_votes 		= 	get_post_meta( get_the_ID(), '_cgc_edu_exercise_total_votes', true );
						$votes_allowed   	= 	get_post_meta( get_post_meta( get_the_ID(), '_cgc_exercise_submission_linked_to', true), '_cgc_edu_exercise_passing', true );
						$voting_still_open 	= 	$total_votes < $votes_allowed;
						$voting_status 		= 	$voting_still_open ? 'voting-open' : 'voting-closed';

						$not_the_submission_author 	= get_current_user_ID() != get_the_author_meta('ID');

						if ( $lesson_id ) {

							$lesson_link   = get_permalink( $lesson_id );
							$lesson_title  = get_the_title( $lesson_id );
						}

					?><article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<!-- Image Mast -->
						<section class="cgc-edu-main cgc-edu-exercise-submission--header">
							<aside class="cgc-edu-exercise-submission--meta">

								<div class="cgc-edu-exercise-submission--connection">
									<i class="cgc-block-icon cgc-block-icon--exercise cgc_tooltip--bottom" data-original-title="Lesson - Hey yo Wes we need a description!"></i>
									<a href="<?php echo esc_url($lesson_link);?>"><?php echo esc_html($lesson_title);?></a>
								</div>

								<div class="cgc-edu-helper cgc-edu-helper--exercise-submission">
									<i class="icon icon-info-sign cgc_tooltip--bottom" data-original-title="How do lessons work? I dunno you tell me!"></i> How do exercises work?
								</div>

								<div class="cgc-edu-list cgc-edu-exercise-critera">
									<strong>Exercise Criteria</strong>
									<p>Images submitted to an exercise must meet the following examples to be given a pass rating.</p>
									<ul class="cgc-edu-block-list">
										<?php if ( !empty( $criteria ) ):

											foreach( (array) $criteria as $key => $data ):

												echo '<li>'.esc_html($data).'</li>';

											endforeach;

										endif; ?>
									</ul>
								</div>

							</aside>
							<div class="cgc-edu-exercise-submission--image-wrap">

								<div class="cgc-edu-exercise-submission--media media-type__<?php echo sanitize_html_class($type);?> cgc-fitvid">
									<div id="cgc-media-loading" class="cgc-media-loading"><div class="cgc-media-loader"></div><span>Loading...</span></div>
									<?php

									switch ($type) {
										case 'image':
											?><div class="media--img" style="background-image:url('<?php echo esc_url($image[0]);?>');"></div><?php
											break;
										case 'sketchfab':
											?><iframe width="100%" height="" src="//sketchfab.com/models/5cfede7837b842edb08439d61b7c3fd1/embed" frameborder="0" allowfullscreen mozallowfullscreen="true" webkitallowfullscreen="true" onmousewheel=""></iframe><?php
											break;
										case 'video':
											var_dump($video);

											if ( 'youtube' == $provider ) {
												?><iframe width="100%" height="" src="//www.youtube.com/embed/<?php echo esc_attr($type_video);?>" frameborder="0" allowfullscreen></iframe><?php
											} elseif( 'vimeo' == $provider ) {
												?><iframe width="100%" height="" src="//player.vimeo.com/video/<?php echo esc_attr($type_video);?>" frameborder="0" allowfullscreen></iframe><?php
											}

											break;
										default:
											?><div style="background-image:url('<?php echo esc_url($image[0]);?>');"></div><?php
											break;
									}


									?>

								</div>

								<div class="cgc-edu-meta <?php echo sanitize_html_class( $voting_status );?>">

									<?php

									$has_voted     = get_user_meta( get_current_user_ID(), '_cgc_edu_exercise-'.get_the_ID().'_has_voted', true);
									$vote_class   = $has_voted ? 'has-voted' : 'not-voted';?>

									<div id="cgc-edu-exercise--vote-info" class="<?php echo $vote_class;?>"><?php echo cgc_edu_exercise_grade();?></div>

									<?php

									// if the current logged in user hasnt voted and they are NOT the author of this submission,
									// and this submission hasn't passed the threshold of allowed voets, then then show the form
									if ( !$has_voted && $not_the_submission_author && $voting_still_open ): ?>
										<form id="cgc-exercise-vote-form" method="post" enctype="multipart/form-data">

											<label for="vote-yes">
												Yes
							                	<input id="vote-yes" type="radio" name="vote" value="yes"/>
							                </label>

											<label for="vote-no">
												No
							                	<input id="vote-no" type="radio" name="vote" value="no"/>
							                </label>
							                <input type="hidden" name="action" value="process_grading">
							                <input type="hidden" name="user_id" value="<?php echo get_current_user_ID(); ?>">
							                <input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">
							                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('cgc-exercise-nonce'); ?>"/>
									        <input id="cgc-exercise-vote" type="submit" value="Submit">
										</form>
									<?php endif; ?>
								</div>
							</div>
							<div class="cgc-edu-bg-filler"></div>
						</section>

						<!-- Content/Discussion -->
						<section class="cgc-edu-main cgc-edu-exercise-submission--entry">
							<div id="cgc-edu-tabs" class="cgc-edu-tabs--exercise">

								<ul class="tab-nav">
									<li class="nav-one"><a href="#about" class="current">About</a></li>
									<li class="nav-two"><a href="#discussion">Discussion</a></li>
								</ul>

								<div class="tab-content">

									<div id="about" class="tab-display">
										<?php echo the_content();?>
									</div>

									<div id="discussion" class="tab-hide tab-display">
										<?php if ( comments_open() || get_comments_number() ) {
											comments_template();
										}?>
									</div>

								</div>

							</div>

							<!-- Sidebar -->
							<div id="cgc-edu-sidebar" class="cgc-edu-sidebar--exercise">
								<div class="cgc-edu-sidebar--block cgc-edu-sidebar--block__author">

									<?php echo cgc_edu_author_block( get_the_ID(), true );?>
								</div>
							</div>
						</div>

					</article>

				<?php endwhile;endif;?>
			</div>
		</div>
	</div>

	<?php if ( function_exists('cgc_edu_grading_modal') ) echo cgc_edu_grading_modal(); ?>

<?php get_footer();