<?php get_header();?>

	<div class="page-content">
		<div class="page-content-inner">
			<div id="main" class="main-content no-sidebar">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post();

					// setup some vars for this template
					$auth_id 		= get_the_author_meta('ID');
					$files 			= cgc_edu_exercise_get_files();
					$submissions 	= cgc_edu_exercise_get_submissions();

					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<header class="cgc-edu-mast">

							<div class="cgc-edu-block-title">
								<i class="cgc-block-icon cgc-block-icon--exercise cgc_tooltip--bottom" title="Exercise - Organized content to help you achieve a goal"></i>
								<?php echo the_title('<h1>','</h1>');?>
							</div>

							<?php get_template_part( 'content', 'post-header' ); ?>

							<div class="cgc-edu-meta">
								<?php cgc_connected_course(true);?>
							</div>

						</header>


						<section class="cgc-edu-main">

							<!-- Tabbed -->
							<div id="cgc-edu-tabs" class="cgc-edu-tabs--exercise">

								<ul class="tab-nav">
									<li class="nav-one"><a href="#about" class="current">About</a></li>
									<li class="nav-two"><a href="#discussion">Discussion</a></li>
									<li class="nav-three"><a href="#files">Exercise Files</a></li>
									<li class="nav-four last"><a href="#submissions">Submissions</a></li>
								</ul>

								<div class="tab-content">

									<div id="about" class="tab-display">
										<?php echo the_content();?>
									</div>

									<div id="discussion" class="tab-hide tab-display">
										<?php if ( comments_open() ) {
											comments_template();
										}?>
									</div>

									<div id="files" class="tab-hide tab-display">
										<h3>Exercise Project Files</h3>
										<?php

										$files_desc = get_post_meta( get_the_ID(), '_cgc_edu_exercise_files_desc', true );

										if ( $files_desc ) {
											echo wpautop( $files_desc );
										}

										if ( $files ) {

											?><ul class="cgc-edu-downloadables"><?php

												foreach( (array) $files as $key => $file ) {

													$title = $link = $size = '';

													$title = $file['_title'];
													$link = $file['_file'];
													$size = isset( $file['_size'] ) ? sprintf('<span>( %s )</span>', $file['_size']) : false;

													echo '<li><a href="'.$link.'">'.$title.' '.$size.'</a></li>';
												}

											?></ul><?php

										} ?>
									</div>

									<div id="submissions" class="tab-hide tab-display">
										<h3>Exercise Submissions</h3>

										<?php if ( $submissions ) {
											?><ul class="cgc-edu-submissions"><?php
		
												foreach( (array) $submissions as $key => $submission ) {

													$id = $submission;
													$sub = get_post($id);

													if ( FALSE !== get_post_status( $id ) ) {

													  	echo cgc_edu_submission_block( $sub );
													}
												}

											?></ul>

										<?php } else { ?>
											<p class="empty">No submissions yet. Perhaps be the first? Submit button is on your right!</p>
										<?php } ?>
									</div>

								</div>

							</div>

							<div id="cgc-edu-sidebar" class="cgc-edu-sidebar--exercise">
								<div class="cgc-edu-sidebar--block cgc-edu-sidebar--block__actions">
									<ul>
										<li><a href="#" data-reveal-id="cgc-exercise-submission-modal"><i class="icon icon-upload"></i>Submit Exercise</a></li>
										<li><a href="#"><i class="icon icon-upload"></i>Add to Watchlist</a></li>
										<li><a href="#"><i class="icon icon-upload"></i>Download HD Video</a></li>
									</ul>
								</div>
								<div class="cgc-edu-sidebar--block cgc-edu-sidebar--block__author">
									<?php echo cgc_edu_author_block();?>
								</div>
								<div class="cgc-edu-sidebar--block cgc-edu-sidebar--block__share">
									<ul>
										<li><a href="#"><i class="icon icon-facebook-sign"></i>Like This</a></li>
										<li><a href="#"><i class="icon icon-twitter-sign"></i>Tweet This</a></li>
										<li><a href="#"><i class="icon icon-pinterest-sign"></i>Pin This</a></li>
									</ul>
								</div>
							</div>

						</section>

					</article>

				<?php endwhile;endif;?>
			</div>
		</div>
	</div>

	<?php if ( function_exists('cgc_edu_exercise_submission_modal') ) echo cgc_edu_exercise_submission_modal() ;?>

<?php get_footer();