<?php get_header();?>

	<div class="page-content">
		<div class="page-content-inner">
			<div id="main" class="main-content no-sidebar">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post();

					// setup some vars for this template
					$post_id 		= get_the_ID();
					$auth_id 		= get_the_author_meta('ID');
					$files 			= cgc_edu_exercise_get_files();
					$submissions 	= cgc_edu_exercise_get_submissions();
					$sub_count      =  $submissions ? sprintf('<span class="cgc-edu-circle-badge">%s</span>', absint( cgc_edu_exercise_count_submissions() ) ) : false;
					$xp_point_value   = get_post_meta( $post_id, '_cgc_edu_exercise_xp_worth', true ) ? sprintf('<span>XP</span>%s', get_post_meta( $post_id, '_cgc_edu_exercise_xp_worth', true ) ) : '0';

					$feat_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );

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

								<div class="cgc-edu-meta--xp">
									<?php echo $xp_point_value;?>
								</div>
							</div>

						</header>


						<section class="cgc-edu-main">

							<!-- Tabbed -->
							<div id="cgc-edu-tabs" class="cgc-edu-tabs--exercise">

								<ul class="tab-nav">
									<li class="nav-one"><a href="#about" class="current">About</a></li>
									<li class="nav-two"><a href="#discussion">Discussion</a></li>
									<li class="nav-three"><a href="#files">Exercise Files</a></li>
									<li class="nav-four last"><a href="#submissions">Submissions <?php echo $sub_count;?></a></li>
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
													$link = is_user_logged_in() && cgc_user_has_download_access( $post_id ) ? $file['_file'] : '#';
													$modal = is_user_logged_in() && cgc_user_has_download_access( $post_id ) ? false : 'data-reveal-id="header-login-form"';
													$size = isset( $file['_size'] ) ? sprintf('<span>( %s )</span>', $file['_size']) : false;

													echo '<li><a href="'.$link.'" '.$modal.'>'.$title.' '.$size.'</a></li>';
												}

											?></ul><?php

										} ?>
									</div>

									<div id="submissions" class="tab-hide tab-display">
										<h3>Exercise Submissions</h3>

										<?php if ( $submissions ) {
											?><ul class="cgc-edu-submissions"><?php

												foreach( (array) $submissions as $key => $id ) {

													$sub = get_post($id);

													if ( FALSE !== get_post_status( $id ) && 'publish' == get_post_status( $id ) ) {

													  	echo cgc_edu_submission_block( $id );
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
										<?php if ( is_user_logged_in() ) { ?>
											<li><a href="#" data-reveal-id="cgc-exercise-submission-modal"><i class="icon icon-upload"></i>Submit Exercise</a></li>
										<?php } else { ?>
											<li><a href="#" href="#" data-reveal-id="header-login-form"><i class="icon icon-upload"></i>Submit Exercise</a></li>
										<?php } ?>
										<li><a href="#"><i class="icon icon-upload"></i>Add to Watchlist</a></li>
										<li><a href="#"><i class="icon icon-upload"></i>Download HD Video</a></li>
									</ul>
								</div>
								<div class="cgc-edu-sidebar--block cgc-edu-sidebar--block__author">
									<?php echo cgc_edu_author_block();?>
								</div>
								<div class="cgc-edu-sidebar--block cgc-edu-sidebar--block__share">
									<ul>
										<li class="cgc-fb-share-link"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink());?>&t=<?php echo the_title();?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=350,width=800');return false;" target="_blank" title="Share on Facebook">Share This</a></li>
										<li class="cgc-twitter-share-link"><a href="http://twitter.com/intent/tweet/?text=What do you think of my @cgcookie image submission? <?php echo get_permalink();?> " onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=350,width=800');return false;" target="_blank" title="Share on Twitter" >Tweet This</a></li>
										<li class="cgc-pinterest-share-link"><a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink());?>&media=<?php echo $feat_image[0];?>&description=<?php echo the_title();?>" class="pin-it-button" count-layout="horizontal"  onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=350,width=800');return false;" target="_blank" >Pin This</a></li>
									</ul>
								</div>
							</div>

						</section>

					</article>

				<?php endwhile;endif;?>
			</div>
		</div>
	</div>

	<?php if ( function_exists('cgc_edu_exercise_submission_modal') && is_user_logged_in() ) echo cgc_edu_exercise_submission_modal() ;?>

<?php get_footer();