<?php get_header(); ?>

	<div class="page-content">
		<div class="page-content-inner">
			<div id="main" class="main-content no-sidebar">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post();

					// setup some vars for this template
					$auth_id = get_the_author_meta('ID');

					?><article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<!-- Image Mast -->
						<section class="cgc-edu-main cgc-edu-exercise-submission--header">
							<aside class="cgc-edu-exercise-submission--meta">

								<div>
									TITLE
								</div>

								<div class="cgc-edu-helper cgc-edu-helper--exercise-submission">
									How do exercises work?
								</div>

								<div class="cgc-edu-list cgc-edu-exercise-critera">
									<strong>Exercise Criteria</strong>
									<ul class="cgc-edu-block-list">
										<li>SOmething</li>
										<li>Someting Else</li>
									</ul>
								</div>

							</aside>
							<div class="cgc-edu-exercise-submission--image-wrap">
								<div class="cgc-edu-exercise-submission--image">
									<div style="background-image:url('http://placekitten.com/1200/800');"></div>
								</div>
								<div class="cgc-edu-meta">
									VOTE AREA
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

									<div id="about">
										<?php echo the_content();?>
									</div>

									<div id="discussion" class="tab-hide">
										DISCUSSION
									</div>

								</div>

							</div>

							<!-- Sidebar -->
							<div id="cgc-edu-sidebar" class="cgc-edu-sidebar--exercise">
								<div class="cgc-edu-sidebar--block cgc-edu-sidebar--block__author">

									<?php

									$avatar = get_user_meta($auth_id, 'profile_avatar_image', true);

									if ( $avatar ) {

										printf('<img src="%s" alt="%s">', $avatar, the_author_meta('display_name',$auth_id) );

									} else {

										echo get_avatar( $auth_id, 80 );
									}
									?>
									<p>Instructor: <?php echo the_author_meta('display_name',$auth_id);?></p>
									<a href="#">Follow</a>
									<p>Big bio</p>
									<a href="#">More by this instructor</a>
								</div>
							</div>
						</div>

					</article>

				<?php endwhile;endif;?>
			</div>
		</div>
	</div>

<?php get_footer();